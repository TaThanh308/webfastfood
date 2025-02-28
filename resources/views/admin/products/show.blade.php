@extends('layouts.admin')

@section('content')
    <div class="container-fluid">
        <h2 class="mb-4">Chi tiết sản phẩm</h2>
        <div class="card">
            <div class="card-header">
                <h3>{{ $product->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Ảnh sản phẩm -->
                    <div class="col-md-4">
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid mb-3">
                    </div>
                    <!-- Thông tin sản phẩm -->
                    <div class="col-md-8">
                        <table class="table table-bordered">
                            <tr>
                                <th>Danh mục</th>
                                <td>{{ $product->category->name }}</td>
                            </tr>
                            <tr>
                                <th>Giá</th>
                                <td>{{ number_format($product->price, 0, ',', '.') }} VND</td>
                            </tr>
                            <tr>
                                <th>Kích thước</th>
                                <td>
                                    @if($product->size)
                                        @foreach(explode(',', $product->size) as $s)
                                            <span class="badge badge-secondary">{{ $s }}</span>
                                        @endforeach
                                    @else
                                        <span>Không có</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Kho</th>
                                <td>{{ $product->stock }}</td>
                            </tr>
                            <tr>
                                <th>Mô tả</th>
                                <td>{{ $product->description }}</td>
                            </tr>
                        </table>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
