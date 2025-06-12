<?php

namespace Modules\Eshop\App\Http\Controllers;

use Modules\Files\App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EshopController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('eshop::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('eshop::create');
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
        return view('eshop::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('eshop::edit');
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
