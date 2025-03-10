<?php

namespace App\Http\Controllers\Api;

use App\Models\Banner;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $position = $request->position;

        $query = Banner::query();

        if($position){
            $query->where('position', $position);
        }

        $banners = $query->orderBy('order_index', 'asc')->get();

        return response()->json($banners);
    }
}
