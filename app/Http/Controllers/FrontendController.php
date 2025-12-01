<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class FrontendController extends Controller
{
    public function index()
    {
        return view('home');
    }
    public function pages(){
        $pages = Page::all();
        return view('pages.index', compact('pages'));
    }
    public function pageDetails($slug){
        $page = Page::where('slug', $slug)->first();
        return view('pages.details', compact('page'));
    }
}
