@extends('layouts.admin')

@section('content')
    <h2>Quản lý Banners</h2>
    <a href="{{ route('banners.create') }}" class="btn btn-primary">Thêm Banner</a>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    <table class="table">
        <thead>
            <tr>
                <th>Tiêu đề</th>
                <th>Hình ảnh</th>
                <th>Liên kết</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($banners as $banner)
                <tr>
                    <td>{{ $banner->title }}</td>
                    <td><img src="{{ asset('storage/' . $banner->image) }}" width="100"></td>
                    <td><a href="{{ $banner->link }}" target="_blank">{{ $banner->link }}</a></td>
                    <td>{{ $banner->status }}</td>
                    <td>
                        <a href="{{ route('banners.edit', $banner->id) }}" class="btn btn-warning">Sửa</a>
                        <form action="{{ route('banners.destroy', $banner->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-3">
                {{ $banners->links('pagination::bootstrap-4') }}
            </div>
@endsection
