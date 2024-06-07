<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::
        // whereNull('parent_id')
                     with('children')
                    //  ->orderBy('sorted_id')
                     ->get();

        return view('sidebar', compact('menus'));
    }
}
