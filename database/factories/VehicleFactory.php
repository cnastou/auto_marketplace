<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Vehicle>
 */
class VehicleFactory extends Factory
{
    protected $model = Vehicle::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $make = $this->faker->randomElement([
            'Ford',
            'Chevrolet',
            'Toyota',
            'Honda',
            'BMW',
            'Mercedes-Benz',
            'Nissan',
            'Tesla',
            'Jeep',
            'GMC',
        ]);

        $model = $this->faker->randomElement([
            'F-150',
            'Silverado',
            'Camry',
            'Accord',
            'X5',
            'C-Class',
            'Altima',
            'Model 3',
            'Wrangler',
            'Sierra',
        ]);

        return [
            'user_id' => User::factory(),
            'make' => $make,
            'model' => $model,
            'year' => $this->faker->numberBetween(2016, 2026),
            'vin' => strtoupper($this->faker->unique()->regexify('[A-HJ-NPR-Z0-9]{17}')),
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
            'status' => 'available',
        ];
    }
}