<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Video;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $perPage = $request->perPage ?? 40;
        $search = $request->search ?? null;
        $orderBy = $request->orderBy ?? 'id';
        $orderDir = strtolower($request->orderDir) === 'asc' ? 'asc' : 'desc';
        $query = Video::query();

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', '%' . $search . '%');

            });
        }


        $item = $query->paginate($perPage);
        // Paginate results with the specified number per page
        return response()->json($item, 200);
    }


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
        $item = Video::findOrFail($id);
        return response()->json($item);
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
