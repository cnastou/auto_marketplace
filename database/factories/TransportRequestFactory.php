<?php

namespace Database\Factories;

use App\Models\TransportRequest;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TransportRequest>
 */
class TransportRequestFactory extends Factory
{
    protected $model = TransportRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pickupDate = $this->faker->dateTimeBetween('+2 days', '+2 months');

        return [
            'user_id' => User::factory(),
            'vehicle_id' => Vehicle::factory(),
            'pickup_date' => $pickupDate->format('Y-m-d'),
            'delivery_date' => $this->faker->dateTimeBetween($pickupDate, '+4 months')->format('Y-m-d'),
            'pickup_location' => $this->faker->randomElement([
                'Dallas, TX',
                'Atlanta, GA',
                'Phoenix, AZ',
                'Chicago, IL',
                'Denver, CO',
                'Miami, FL',
                'Seattle, WA',
                'Los Angeles, CA',
                'Houston, TX',
                'Charlotte, NC',
            ]),
            'delivery_location' => $this->faker->randomElement([
                'Dallas, TX',
                'Atlanta, GA',
                'Phoenix, AZ',
                'Chicago, IL',
                'Denver, CO',
                'Miami, FL',
                'Seattle, WA',
                'Los Angeles, CA',
                'Houston, TX',
                'Charlotte, NC',
            ]),
            'status' => 'pending',
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}