<?php

namespace Database\Factories;

use App\Models\Customer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Car>
 */
class CarFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $customer = new Customer;
        $customer->save();
        return [
            'brand' => $this->faker->company,
            'color' => $this->faker->colorName(),
            'license_plate' => $this->faker->unique()->regexify('[A-Z0-9]{3}-[A-Z0-9]{3}-[A-Z0-9]{3}'),
            'type' => $this->faker->randomElement([
                'sedan', 
                'hatchback', 
                'suv', 
                'coupe', 
                'convertible', 
                'crossover', 
                'van', 'pickup', 
                'truck', 
                'bus', 
                'motorcycle', 
                'other'
            ]),
            'customer_id' => $customer->id
        ];
    }
}
