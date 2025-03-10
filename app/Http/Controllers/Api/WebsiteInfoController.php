<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WebsiteInfo;

class WebsiteInfoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function
    index()
    {
        $websiteInfo = WebsiteInfo::first(); // Optimized query

        if (!$websiteInfo) {
            return response()->json(['message' => 'No website info found'], 404);
        }

        return response()->json($websiteInfo);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
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
