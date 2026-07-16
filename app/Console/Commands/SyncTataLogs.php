<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\TataCallLog;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SyncTataLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tata:sync-logs {--from= : Start date for sync (Y-m-d or Y-m-d H:i:s)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync call logs from Tata Smartflo API for Amra Property CRM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $apiKey = config('services.smartflo.api_key');
        $targetDid = config('services.smartflo.did_number'); // Can filter specific DID if set

        if (empty($apiKey)) {
            $this->error('TTBS_API_KEY is not configured in .env');
            return 1;
        }

        $this->info('Starting Tata Smartflo call logs sync...');

        $fromDateOption = $this->option('from');
        if ($fromDateOption) {
            $startDateStr = Carbon::parse($fromDateOption)->format('Y-m-d H:i:s');
        } else {
            $lastSyncTime = TataCallLog::max('start_time');
            if ($lastSyncTime) {
                // Fetch calls starting from 1 second after the last sync time to avoid duplicates
                $startDateStr = Carbon::parse($lastSyncTime)->addSecond()->format('Y-m-d H:i:s');
            } else {
                $startDateStr = '2026-05-01 00:00:00';
            }
        }

        $this->info("Syncing logs starting from: {$startDateStr}");

        $page = 1;
        $limit = 100;
        $syncCount = 0;
        $newCount = 0;

        do {
            $apiUrl = "https://api-smartflo.tatateleservices.com/v1/call/records?limit={$limit}&page={$page}"
                . "&from_date=" . urlencode($startDateStr);

            $response = Http::timeout(30)
                ->retry(2, 1000)
                ->withHeaders([
                    'Accept' => 'application/json',
                    'Authorization' => $apiKey
                ])
                ->get($apiUrl);

            if ($response->failed()) {
                $this->error("API failed on Page {$page} (HTTP " . $response->status() . ").");
                return 1;
            }

            $data = $response->json();
            if (!is_array($data) || !isset($data['results'])) {
                $this->error("Invalid response format on Page {$page}.");
                return 1;
            }

            $results = $data['results'];
            if (empty($results)) {
                break;
            }

            foreach ($results as $record) {
                $callId = $record['call_id'] ?? $record['uuid'] ?? null;
                if (!$callId) {
                    continue;
                }

                $clientNumber = $record['client_number'] ?? $record['caller_id_num'] ?? '';
                $didNumber = $record['did_number'] ?? $record['caller_id_num'] ?? '';

                // Normalize numbers: strip non-digits and country prefix 91 if present
                $callerNumber = preg_replace('/\D/', '', $clientNumber);
                $tfnNumber = preg_replace('/\D/', '', $didNumber);

                if (str_starts_with($callerNumber, '91') && strlen($callerNumber) === 12) {
                    $callerNumber = substr($callerNumber, 2);
                }
                if (str_starts_with($tfnNumber, '91') && strlen($tfnNumber) === 12) {
                    $tfnNumber = substr($tfnNumber, 2);
                }

                // If a specific target DID is configured, filter out other logs
                if (!empty($targetDid) && $tfnNumber !== preg_replace('/\D/', '', $targetDid)) {
                    continue;
                }

                $duration = (int)($record['total_call_duration'] ?? $record['call_duration'] ?? 0);
                $rawStatus = $record['status'] ?? 'Unknown';

                // Standardize status
                $status = 'Unknown';
                if (stripos($rawStatus, 'answer') !== false || stripos($rawStatus, 'success') !== false) {
                    $status = 'Answered';
                } elseif (stripos($rawStatus, 'miss') !== false) {
                    $status = 'Missed';
                } elseif (stripos($rawStatus, 'abandon') !== false) {
                    $status = 'Abandoned';
                } elseif (stripos($rawStatus, 'busy') !== false) {
                    $status = 'Busy';
                } else {
                    $status = ucfirst(strtolower(trim($rawStatus)));
                }

                // Parse timestamps
                $startTime = null;
                if (!empty($record['date']) && !empty($record['time'])) {
                    $startTime = $record['date'] . ' ' . $record['time'];
                } elseif (!empty($record['start_stamp'])) {
                    $startTime = $record['start_stamp'];
                }

                $endTime = $record['end_stamp'] ?? null;
                if (!$endTime && $startTime && $duration > 0) {
                    $endTime = date('Y-m-d H:i:s', strtotime($startTime) + $duration);
                }

                $existingLog = TataCallLog::where('call_id', $callId)->first();
                if (!$existingLog) {
                    $newCount++;
                }

                TataCallLog::updateOrCreate([
                    'call_id' => $callId,
                ], [
                    'caller_number' => $callerNumber,
                    'tfn_number' => $tfnNumber,
                    'duration_seconds' => $duration,
                    'status' => $status,
                    'disposition' => $record['reason_key'] ?? $record['hangup_cause_description'] ?? null,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'recording_url' => $record['recording_url'] ?? null,
                    'raw_data' => $record,
                ]);

                $syncCount++;
            }

            if (count($results) < $limit) {
                break;
            }

            $page++;
            usleep(100000); // 100ms sleep to prevent hitting rate limits
        } while (true);

        $this->info("Tata call logs sync completed. Synced {$syncCount} logs total (New Today: {$newCount} logs).");
        return 0;
    }
}
