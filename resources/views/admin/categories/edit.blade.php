@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Chỉnh sửa danh mục</h2>
    <form action="{{ route('admin.categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label>Tên danh mục</label>
            <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
        </div>
        <button type="submit" class="btn btn-warning">Cập nhật</button>
    </form>
</div>
@endsection
