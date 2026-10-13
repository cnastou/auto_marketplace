<?php

namespace App\Services;

use App\Models\Bid;
use App\Models\TransportRequest;
use Illuminate\Support\Facades\Cache;

class DashboardStatsService
{
    public function get(): array
    {
        return Cache::remember(
            'marketplace:dashboard:stats',
            60,
            fn () => [
                'transport_requests' => TransportRequest::count(),
                'bids' => Bid::count(),
                'pending_bids' => Bid::where(
                    'status',
                    'pending'
                )->count(),
            ]
        );
    }
}
