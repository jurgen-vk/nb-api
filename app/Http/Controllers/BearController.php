<?php

namespace App\Http\Controllers;

use App\Models\Bear;
use Illuminate\Http\Request;

class BearController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bears = Bear::all();

        return response()->json([
            'data' => $bears,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $bear = Bear::findOrFail($id);

        $bear = get_object_vars($bear->location);

        return response()->json([
            'data' => $bear,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
