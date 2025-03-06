@extends('layouts.admin')

@section('content')
<div class="container">
    <h1 class="my-4">Danh Sách Giảm Giá</h1>

    <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary mb-3">+ Thêm giảm giá</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Sản phẩm</th>
                <th>Giảm giá (%)</th>
                <th>Số tiền giảm (VNĐ)</th>
                <th>Bắt đầu</th>
                <th>Kết thúc</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($discounts as $discount)
                <tr>
                    <td>{{ $discount->id }}</td>
                    <td>{{ $discount->product->name }}</td>
                    <td class="text-danger fw-bold">{{ $discount->discount_percentage }}%</td>
                    <td class="text-success fw-bold">
                        {{ number_format($discount->product->price * ($discount->discount_percentage / 100), 0, ',', '.') }} VNĐ
                    </td>
                    <td>{{ $discount->start_date }}</td>
                    <td>{{ $discount->valid_until }}</td>
                    <td>
                        <a href="{{ route('admin.discounts.edit', $discount->id) }}" class="btn btn-warning btn-sm">✏️</a>
                        <form action="{{ route('admin.discounts.destroy', $discount->id) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">🗑️</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
