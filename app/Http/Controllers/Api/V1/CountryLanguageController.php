<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryLanguageRequest;
use App\Http\Requests\UpdateCountryLanguageRequest;
use App\Http\Resources\CountryLanguageCollection;
use App\Http\Resources\CountryLanguageResource;
use App\Models\CountryLanguage;
use Illuminate\Http\JsonResponse;

class CountryLanguageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CountryLanguageCollection
    {
        return new CountryLanguageCollection(CountryLanguage::with('country')->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryLanguageRequest $request): CountryLanguageResource
    {
        $countryLanguage = CountryLanguage::create($request->validated());

        return new CountryLanguageResource($countryLanguage->load('country'));
    }

    /**
     * Display the specified resource.
     */
    public function show(CountryLanguage $countryLanguage): CountryLanguageResource
    {
        return new CountryLanguageResource($countryLanguage->load('country'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryLanguageRequest $request, CountryLanguage $countryLanguage): CountryLanguageResource
    {
        $countryLanguage->update($request->validated());

        return new CountryLanguageResource($countryLanguage->load('country'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CountryLanguage $countryLanguage): JsonResponse
    {
        $countryLanguage->delete();

        return response()->json(null, 204);
    }
}
