@extends('layouts.admin')

@section('title', 'Danh sách bình luận')

@section('content')
<div class="container">
    <h3 class="text-center">💬 Danh sách bình luận</h3>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Người bình luận</th>
                <th>Sản phẩm</th>
                <th>Nội dung</th>
                <th>Đánh giá</th>
                <th>Thời gian</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($comments as $index => $comment)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $comment->user->name }}</td>
                <td>{{ $comment->product->name }}</td>
                <td>{{ $comment->comment }}</td>
                <td>{{ $comment->rating }}/5 ⭐</td>
                <td>{{ $comment->created_at->format('d-m-Y H:i') }}</td>
                <td>
                    <form action="{{ route('admin.comments.delete', $comment->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center">
        {{ $comments->links() }}
        </div>
</div>          
@endsection
