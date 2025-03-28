<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\News;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function index()
    {
        $news = News::latest()->paginate(10);
        return view('admin.news.index', compact('news'));
    }

    public function create()
    {
        return view('admin.news.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('news_images', 'public') : null;

        News::create([
            'title' => $request->title,
            'content' => $request->content,
            'image' => $imagePath,
            'author' => Auth::user()->name,
            'status' => $request->status,
        ]);

        return redirect()->route('news.index')->with('success', 'Bài viết đã được tạo!');
    }

    public function edit($id)
    {
        $news = News::findOrFail($id);
        return view('admin.news.edit', compact('news'));
    }

    public function update(Request $request, $id)
    {
        $news = News::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'image' => 'nullable|image|max:2048',
            'status' => 'required|in:draft,published',
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('news_images', 'public');
            $news->image = $imagePath;
        }

        $news->update([
            'title' => $request->title,
            'content' => $request->content,
            'status' => $request->status,
        ]);

        return redirect()->route('news.index')->with('success', 'Bài viết đã được cập nhật!');
    }

    public function destroy($id)
    {
        $news = News::findOrFail($id);
        if ($news->image) {
            \Storage::delete('public/' . $news->image);
        }
        $news->delete();

        return redirect()->route('news.index')->with('success', 'Bài viết đã được xóa!');
    }
}
