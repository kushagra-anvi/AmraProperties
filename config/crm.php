<?php

return [
    'roles' => [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'sales_team' => 'Sales Team',
        'analyst' => 'Analyst',
    ],

    'lead_sources' => [
        'meta' => 'Meta Ads',
        'google' => 'Google Ads',
        'website' => 'Website',
        'manual' => 'Manual Upload',
        'csv' => 'CSV Upload',
    ],

    'b2b_categories' => [
        'agent' => 'Agent',
        'developer' => 'Developer',
        'single_owner' => 'Single Property Owner',
    ],

    'b2b_statuses' => [
        'new' => 'New',
        'contacted' => 'Contacted',
        'qualified' => 'Qualified',
        'not_interested' => 'Not Interested',
        'follow_up' => 'Follow-up',
        'free_listing' => 'Free Listing',
        'paid_listing' => 'Paid Listing',
        'converted' => 'Converted',
    ],

    'b2c_statuses' => [
        'new' => 'New',
        'filtered' => 'Admin Filtered',
        'shared' => 'Shared',
        'closed' => 'Closed',
        'not_interested' => 'Not Interested',
    ],

    'property_types' => [
        'plot' => 'Plot',
        'flat' => 'Flat',
        'villa' => 'Villa',
        'commercial' => 'Commercial',
    ],

    'configurations' => [
        '1BHK' => '1BHK',
        '2BHK' => '2BHK',
        '3BHK' => '3BHK',
        '4BHK' => '4BHK',
        'Plot' => 'Plot',
        'Studio' => 'Studio',
    ],

    'partner_types' => [
        'agent' => 'Agent',
        'developer' => 'Developer',
        'affiliate' => 'Affiliate Partner',
    ],

    'packages' => [
        'free' => 'Free',
        'starter' => 'Starter',
        'growth' => 'Growth',
        'premium' => 'Premium',
        'customise' => 'Customise',
    ],

    'auto_distribution' => [
        'enabled' => true,
        'lookback_days' => 30,
        'package_limits' => [
            'free' => 0,
            'starter' => 1,
            'growth' => 2,
            'premium' => 3,
            'customise' => 3,
        ],
        'package_priority' => [
            'customise' => 50,
            'premium' => 40,
            'growth' => 30,
            'starter' => 20,
            'free' => 0,
        ],
    ],

    'date_filters' => [
        'today' => 'Today',
        'yesterday' => 'Yesterday',
        'last_7_days' => 'Last 7 Days',
        'last_30_days' => 'Last 30 Days',
        'custom' => 'Custom Date Range',
    ],
];
