<?php

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'status' => fake()->boolean(80),
            'iso2_code' => fake()->unique()->countryCode(),
            'phone_code' => '+'.fake()->numberBetween(1, 999),
        ];
    }

    public function active(): static
    {
        return $this->state(['status' => true]);
    }

    public function inactive(): static
    {
        return $this->state(['status' => false]);
    }
}
