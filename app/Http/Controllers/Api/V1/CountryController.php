<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Http\Resources\CountryCollection;
use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): CountryCollection
    {
        return new CountryCollection(Country::with('countryLanguages')->latest()->paginate());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCountryRequest $request): CountryResource
    {
        $country = Country::create($request->validated());

        return new CountryResource($country->load('countryLanguages'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Country $country): CountryResource
    {
        return new CountryResource($country->load('countryLanguages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCountryRequest $request, Country $country): CountryResource
    {
        $country->update($request->validated());

        return new CountryResource($country->load('countryLanguages'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Country $country): JsonResponse
    {
        $country->delete();

        return response()->json(null, 204);
    }
}
