<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class CountryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [
            'id' => $this->id,
            'iso2_code' => $this->iso2_code,
            'phone_code' => $this->phone_code,
            'country_languages' => CountryLanguageResource::collection($this->whenLoaded('countryLanguages')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if (Auth::user()?->is_admin) {
            $result['status'] = $this->status;
        }

        return $result;
    }
}
