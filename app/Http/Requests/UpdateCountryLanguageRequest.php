<?php

namespace App\Http\Requests;

use App\Enums\Language;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCountryLanguageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'country_id' => ['sometimes', 'exists:countries,id'],
            'title' => ['sometimes', 'string', 'max:255'],
            'abbreviation' => ['sometimes', 'string', 'max:10'],
            'language_id' => ['sometimes', Rule::enum(Language::class)],
        ];
    }
}
