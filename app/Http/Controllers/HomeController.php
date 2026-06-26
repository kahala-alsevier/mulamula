<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\FlowerMeaning;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $flowerMeanings = FlowerMeaning::all();

        return view('welcome', compact('categories', 'flowerMeanings'));
    }
}
