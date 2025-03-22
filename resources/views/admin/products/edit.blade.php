@extends('layouts.admin')

@section('content')
    <h2>Sửa sản phẩm</h2>
    <div class="card">
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <table class="table table-bordered">
                    <tr>
                        <th><label for="name">Tên sản phẩm:</label></th>
                        <td>
                            <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="category_id">Danh mục:</label></th>
                        <td>
                            <select name="category_id" id="category_id" class="form-control">
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Giá:</th>
                        <td>
                            <div class="input-group">
                                <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
                                <span class="input-group-text">₫</span>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="size">Kích thước:</label></th>
                        <td>
                            <select name="size" id="size" class="form-control">
                                <option value="">-- Chọn kích thước --</option>
                                <option value="S" {{ $product->size == 'S' ? 'selected' : '' }}>S</option>
                                <option value="M" {{ $product->size == 'M' ? 'selected' : '' }}>M</option>
                                <option value="L" {{ $product->size == 'L' ? 'selected' : '' }}>L</option>
                                <option value="XL" {{ $product->size == 'XL' ? 'selected' : '' }}>XL</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="stock">Kho:</label></th>
                        <td>
                            <input type="number" name="stock" id="stock" class="form-control" value="{{ $product->stock }}" required>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="description">Mô tả:</label></th>
                        <td>
                            <textarea name="description" id="description" class="form-control" required>{{ $product->description }}</textarea>
                        </td>
                    </tr>
                    <tr>
                        <th><label for="image">Ảnh:</label></th>
                        <td>
                            @if ($product->image)
                                <div class="mb-2">
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" width="100">
                                </div>
                            @endif
                            <input type="file" name="image" id="image" class="form-control">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" class="text-center">
                            <button type="submit" class="btn btn-primary">Cập nhật</button>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
@endsection
