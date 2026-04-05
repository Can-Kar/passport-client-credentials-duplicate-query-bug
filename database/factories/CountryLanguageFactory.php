<?php

namespace Database\Factories;

use App\Enums\Language;
use App\Models\Country;
use App\Models\CountryLanguage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CountryLanguage>
 */
class CountryLanguageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country_id' => Country::factory(),
            'title' => fake()->country(),
            'abbreviation' => fake()->unique()->lexify('??'),
            'language_id' => fake()->randomElement(Language::cases()),
        ];
    }
}
