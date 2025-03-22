@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Danh sách tin tức</h2>
    <a href="{{ route('news.create') }}" class="btn btn-primary">Thêm bài viết</a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Tác giả</th>
                <th>Ảnh</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($news as $item)
            <tr>
                <td>{{ $item->title }}</td>
                <td>{{ $item->author }}</td>
                <td>
                    @if($item->image)
                        <img src="{{ asset('storage/' . $item->image) }}" width="100">
                    @else
                        Không có ảnh
                    @endif
                </td>
                <td>{{ ucfirst($item->status) }}</td>
                <td>
                    <a href="{{ route('news.edit', $item->id) }}" class="btn btn-warning">Sửa</a>
                    <form action="{{ route('news.destroy', $item->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
