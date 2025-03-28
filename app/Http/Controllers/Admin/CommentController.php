<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index()
    {
        $comments = Review::with(['user', 'product'])->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.comments.index', compact('comments'));
    }

    public function destroy($id)
    {
        Review::findOrFail($id)->delete();
        return redirect()->route('admin.comments')->with('success', 'Đã xóa bình luận.');
    }
}
