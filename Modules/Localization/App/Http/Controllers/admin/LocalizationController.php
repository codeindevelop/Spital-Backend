<?php

namespace Modules\Localization\App\Http\Controllers\admin;

use Modules\Files\App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Localization\App\Models\countries\Country;

class LocalizationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function allCountries(): \Illuminate\Http\JsonResponse
    {
        $countries = Country::all();

        return response()->json([
            'data' => [
                'countries' => $countries,
            ]
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('localization::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        //
    }

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('localization::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('localization::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
