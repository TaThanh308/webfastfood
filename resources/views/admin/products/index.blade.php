@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Danh sách sản phẩm</h2>
        <form action="{{ route('products.index') }}" method="GET" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Tìm kiếm sản phẩm..." value="{{ request('search') }}">
                <button class="btn btn-outline-secondary" type="submit">Tìm</button>
            </div>
        </form>
        <div class="mb-3">
            <a href="{{ route('products.create') }}" class="btn btn-primary">Thêm sản phẩm</a>
        </div>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Ảnh</th>
                        <th>Tên</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Kích thước</th>
                        <th>Kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                <img src="{{ asset('storage/' . $product->image) }}" width="50" alt="{{ $product->name }}">
                            </td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td>{{ number_format($product->price, 0, ',', '.') }} ₫</td>
                            <td>
                                @if($product->size)
                                    @foreach(explode(',', $product->size) as $s)
                                        <span class="badge badge-secondary">{{ $s }}</span>
                                    @endforeach
                                @else
                                    <span>Không có</span>
                                @endif
                            </td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="{{ route('products.show', $product->id) }}" class="btn btn-info btn-sm">Xem</a>
                                <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">Sửa</a>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Xóa sản phẩm?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
