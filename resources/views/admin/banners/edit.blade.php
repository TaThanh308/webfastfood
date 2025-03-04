@extends('layouts.admin')

@section('content')
    <h2>Sửa Banner</h2>
    <form action="{{ route('banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="form-group">
            <label>Tiêu đề:</label>
            <input type="text" name="title" class="form-control" value="{{ $banner->title }}">
        </div>
        

        <div class="form-group">
            <label>Hình ảnh hiện tại:</label>
            <br>
            <img src="{{ asset('storage/' . $banner->image) }}" width="150">
        </div>

        <div class="form-group">
            <label>Chọn hình ảnh mới (nếu muốn thay đổi):</label>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="form-group">
            <label>Liên kết:</label>
            <input type="url" name="link" class="form-control" value="{{ $banner->link }}">
        </div>

        <div class="form-group">
            <label>Trạng thái:</label>
            <select name="status" class="form-control">
                <option value="active" {{ $banner->status == 'active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ $banner->status == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
            </select>
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
    </form>
@endsection
