<?php

namespace App\Http\Controllers\Api;

use App\Models\Page;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Image;

class PageController extends Controller
{
    public function index(Request $request)
    {
        $position = $request->position;
        $type = $request->type;
        $search = $request->search;

        $query = Page::query();

        if($position){
            $query->where('position', $position);
        }
        if($type){
            $query->where('type', $type);
        }

        if ($search) {
            $query->where(function ($sub_query) use ($search) {
                $sub_query->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('short_decription', 'LIKE', '%' . $search . '%');
            });
        }

        $pages = $query->orderBy('order_index', 'asc')->get();

        return response()->json($pages);
    }
}
