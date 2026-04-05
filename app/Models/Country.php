<?php

namespace App\Models;

use Database\Factories\CountryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['status', 'iso2_code', 'phone_code'])]
class Country extends Model
{
    /** @use HasFactory<CountryFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function countryLanguages(): HasMany
    {
        return $this->hasMany(CountryLanguage::class);
    }
}
