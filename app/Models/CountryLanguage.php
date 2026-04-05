<?php

namespace App\Models;

use App\Enums\Language;
use Database\Factories\CountryLanguageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['country_id', 'title', 'abbreviation', 'language_id'])]
class CountryLanguage extends Model
{
    /** @use HasFactory<CountryLanguageFactory> */
    use HasFactory;

    protected function casts(): array
    {
        return [
            'language_id' => Language::class,
        ];
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
}
