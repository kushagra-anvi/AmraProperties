<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->dropForeign(['partner_id']);
        });

        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->foreignId('sales_person_id')->nullable()->after('partner_id')
                ->constrained('sales_people')->nullOnDelete();
            $table->string('recipient_type')->default('agent')->after('sales_person_id')->index();
            $table->string('recipient_name')->nullable()->after('recipient_type');
            $table->string('recipient_contact')->nullable()->after('recipient_name');
        });

        DB::table('b2_c_lead_shares')
            ->join('partners', 'partners.id', '=', 'b2_c_lead_shares.partner_id')
            ->select('b2_c_lead_shares.id', 'partners.type', 'partners.company_name', 'partners.phone')
            ->orderBy('b2_c_lead_shares.id')
            ->each(function ($share) {
                DB::table('b2_c_lead_shares')->where('id', $share->id)->update([
                    'recipient_type' => $share->type === 'developer' ? 'builder' : 'agent',
                    'recipient_name' => $share->company_name,
                    'recipient_contact' => $share->phone,
                ]);
            });

        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->foreignId('partner_id')->nullable()->change();
            $table->foreign('partner_id')->references('id')->on('partners')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('b2_c_lead_shares', function (Blueprint $table) {
            $table->dropForeign(['sales_person_id']);
            $table->dropColumn(['sales_person_id', 'recipient_type', 'recipient_name', 'recipient_contact']);
        });
    }
};
