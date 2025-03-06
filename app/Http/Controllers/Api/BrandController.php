<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $with_books = $request->with_books;

        $query = Brand::query();

        if ($with_books == 1) {
            $query->with(['books' => function ($sub_query) {
                $sub_query->limit(12);
            }]);
        }

        $brands = $query->orderBy('order_index')->orderBy('name')->where('status', 1)->get();


        //Brand::query()->with('books')->orderBy('order_index')->orderBy('name')->get();

        return response()->json($brands);
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
