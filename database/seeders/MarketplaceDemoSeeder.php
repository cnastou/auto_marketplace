<?php

namespace Database\Seeders;

use App\Models\Bid;
use App\Models\TransportRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MarketplaceDemoSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            $customer = User::firstOrCreate(
                ['email' => 'alex.morgan.demo@example.com'],
                [
                    'name' => 'Alex Morgan',
                    'password' => Hash::make('password'),
                    'email_verified_at' => now(),
                ]
            );

            $vehicle = Vehicle::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'make' => 'Toyota',
                    'model' => 'Camry',
                    'year' => 2023,
                ],
                [
                    'vin' => 'DEMO1234567890001',
                    'pickup_location' => 'Chicago, IL',
                    'delivery_location' => 'Atlanta, GA',
                    'status' => 'available',
                ]
            );

            $request = TransportRequest::firstOrCreate(
                [
                    'user_id' => $customer->id,
                    'vehicle_id' => $vehicle->id,
                    'notes' => 'Marketplace demo shipment',
                ],
                [
                    'pickup_date' => now()->addDays(7)->toDateString(),
                    'delivery_date' => now()->addDays(10)->toDateString(),
                    'pickup_location' => 'Chicago, IL',
                    'delivery_location' => 'Atlanta, GA',
                    'status' => 'pending',
                ]
            );

            $carriers = [
                [
                    'name' => 'Jordan Lee',
                    'email' => 'jordan.lee.demo@example.com',
                    'amount' => 850.00,
                ],
                [
                    'name' => 'Taylor Brooks',
                    'email' => 'taylor.brooks.demo@example.com',
                    'amount' => 925.00,
                ],
                [
                    'name' => 'Sam Rivera',
                    'email' => 'sam.rivera.demo@example.com',
                    'amount' => 790.00,
                ],
            ];

            foreach ($carriers as $carrier) {
                $user = User::firstOrCreate(
                    ['email' => $carrier['email']],
                    [
                        'name' => $carrier['name'],
                        'password' => Hash::make('password'),
                        'email_verified_at' => now(),
                    ]
                );

                Bid::firstOrCreate(
                    [
                        'transport_request_id' => $request->id,
                        'user_id' => $user->id,
                    ],
                    [
                        'amount' => $carrier['amount'],
                        'status' => 'pending',
                        'notes' => 'Marketplace demo offer',
                    ]
                );
            }
        });

        Cache::forget('marketplace:dashboard:stats');
    }
}
