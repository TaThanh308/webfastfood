@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh Sửa Bài Viết</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Tiêu đề</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ $news->title }}" required>
        </div>

        <div class="mb-3">
            <label for="content" class="form-label">Nội dung</label>
            <textarea class="form-control" id="content" name="content" rows="5" required>{{ $news->content }}</textarea>
        </div>

        <div class="mb-3">
            <label for="image" class="form-label">Hình ảnh</label>
            <input type="file" class="form-control" id="image" name="image" accept="image/*">
            @if($news->image)
                <p>Ảnh hiện tại:</p>
                <img src="{{ asset('storage/' . $news->image) }}" width="150">
            @endif
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Trạng thái</label>
            <select class="form-control" id="status" name="status" required>
                <option value="draft" {{ $news->status == 'draft' ? 'selected' : '' }}>Nháp</option>
                <option value="published" {{ $news->status == 'published' ? 'selected' : '' }}>Xuất bản</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ route('news.index') }}" class="btn btn-secondary">Quay lại</a>
    </form>
</div>
@endsection
