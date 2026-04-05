<?php

namespace Database\Seeders;

use App\Enums\Language;
use App\Models\Country;
use App\Models\CountryLanguage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['iso2_code' => 'TR', 'phone_code' => '+90', 'names' => ['tr' => 'Turkiye', 'en' => 'Turkey']],
            ['iso2_code' => 'US', 'phone_code' => '+1', 'names' => ['tr' => 'Amerika Birlesik Devletleri', 'en' => 'United States']],
            ['iso2_code' => 'GB', 'phone_code' => '+44', 'names' => ['tr' => 'Birlesik Krallik', 'en' => 'United Kingdom']],
            ['iso2_code' => 'DE', 'phone_code' => '+49', 'names' => ['tr' => 'Almanya', 'en' => 'Germany']],
            ['iso2_code' => 'FR', 'phone_code' => '+33', 'names' => ['tr' => 'Fransa', 'en' => 'France']],
            ['iso2_code' => 'IT', 'phone_code' => '+39', 'names' => ['tr' => 'Italya', 'en' => 'Italy']],
            ['iso2_code' => 'ES', 'phone_code' => '+34', 'names' => ['tr' => 'Ispanya', 'en' => 'Spain']],
            ['iso2_code' => 'JP', 'phone_code' => '+81', 'names' => ['tr' => 'Japonya', 'en' => 'Japan']],
            ['iso2_code' => 'BR', 'phone_code' => '+55', 'names' => ['tr' => 'Brezilya', 'en' => 'Brazil']],
            ['iso2_code' => 'AU', 'phone_code' => '+61', 'names' => ['tr' => 'Avustralya', 'en' => 'Australia']],
        ];

        foreach ($countries as $countryData) {
            $country = Country::create([
                'status' => true,
                'iso2_code' => $countryData['iso2_code'],
                'phone_code' => $countryData['phone_code'],
            ]);

            CountryLanguage::create([
                'country_id' => $country->id,
                'title' => $countryData['names']['tr'],
                'abbreviation' => $countryData['iso2_code'],
                'language_id' => Language::Turkish,
            ]);

            CountryLanguage::create([
                'country_id' => $country->id,
                'title' => $countryData['names']['en'],
                'abbreviation' => $countryData['iso2_code'],
                'language_id' => Language::English,
            ]);
        }

        // Country::factory(50)->has(CountryLanguage::factory()->count(2))->create();
    }
}
