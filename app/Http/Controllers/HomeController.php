<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Models\Banner;
use App\Models\News;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::where('status', 'active')->get();
        $reviews = Review::with('user', 'product')->latest()->take(6)->get(); // Lấy 6 đánh giá mới nhất
        $news = News::where('status', 'published')->latest()->take(3)->get(); // Lấy 5 tin tức mới nhất
    
        return view('home', compact('banners', 'reviews', 'news'));
    }

    
}
