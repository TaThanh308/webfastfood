<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index()
    {
        $banners = Banner::all();
        return view('admin.banners.index', compact('banners'));
    }

    public function create()
    {
        return view('admin.banners.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        $imagePath = $request->file('image')->store('banners', 'public');

        Banner::create([
            'title' => $request->title,
            'image' => $imagePath,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return redirect()->route('banners.index')->with('success', 'Thêm banner thành công!');
    }

    public function edit(Banner $banner)
    {
        return view('admin.banners.edit', compact('banner'));
    }

    public function update(Request $request, Banner $banner)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|url',
            'status' => 'required|in:active,inactive',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete('public/' . $banner->image);
            $imagePath = $request->file('image')->store('banners', 'public');
            $banner->update(['image' => $imagePath]);
        }

        $banner->update([
            'title' => $request->title,
            'link' => $request->link,
            'status' => $request->status,
        ]);

        return redirect()->route('banners.index')->with('success', 'Cập nhật banner thành công!');
    }

    public function destroy(Banner $banner)
    {
        Storage::delete('public/' . $banner->image);
        $banner->delete();

        return redirect()->route('banners.index')->with('success', 'Xóa banner thành công!');
    }
}
