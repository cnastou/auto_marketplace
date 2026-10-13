<?php

namespace App\Services;

use App\Models\Bid;
use App\Models\TransportRequest;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DashboardStatsService
{
    public function get(): array
    {
        $cacheMiss = false;

        $stats = Cache::store('redis')->remember(
            'marketplace:dashboard:stats',
            60,
            function () use (&$cacheMiss) {
                $cacheMiss = true;

                return [
                    'transport_requests' => TransportRequest::count(),
                    'bids' => Bid::count(),
                    'pending_bids' => Bid::where(
                        'status',
                        'pending'
                    )->count(),
                ];
            }
        );

        Log::info(
            $cacheMiss ? 'REDIS CACHE MISS - Querying MySQL' :
                         'REDIS CACHE HIT - Using cached statistics'
        );

        return $stats;
    }
}
