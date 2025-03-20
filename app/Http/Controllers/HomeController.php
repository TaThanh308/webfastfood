<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Banner;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')->get();
        $reviews = Review::with('user', 'product')->latest()->take(10)->get(); // Lấy 10 đánh giá mới nhất

        return view('home', compact('banners', 'reviews'));
    }

}
