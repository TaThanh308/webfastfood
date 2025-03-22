@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Thông tin thanh toán</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="border p-4 rounded shadow-sm mb-4">
                <h4 class="mb-3">Danh sách sản phẩm</h4>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th>Giá</th>
                            <th>Số lượng</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($cartItems as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>
                                @if ($item->discount_percentage)
                                    <span class="text-danger">{{ number_format($item->final_price, 0, ',', '.') }} VNĐ</span>
                                    <del class="text-muted">{{ number_format($item->price, 0, ',', '.') }} VNĐ</del>
                                    <small class="text-success">(-{{ $item->discount_percentage }}%)</small>
                                @else
                                    {{ number_format($item->price, 0, ',', '.') }} VNĐ
                                @endif
                            </td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->final_price * $item->quantity, 0, ',', '.') }} VNĐ</td>
                        </tr>
                    @endforeach

                    </tbody>
                </table>
                <h5 class="text-end">Tổng thanh toán: <strong>{{ number_format($totalPrice, 0, ',', '.') }} VNĐ</strong></h5>
            </div>

            <form action="{{ route('checkout.cod') }}" method="POST" class="border p-4 rounded shadow-sm">
                @csrf
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                <div class="mb-3">
                    <label class="form-label">Địa chỉ giao hàng:</label>
                    <input type="text" name="shipping_address" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Số điện thoại:</label>
                    <input type="text" name="phone_number" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">Thanh toán khi nhận hàng</button>
            </form>

            <form action="{{ route('checkout.vnpay') }}" method="POST" class="border p-4 rounded shadow-sm mt-3">
                @csrf
                <input type="hidden" name="total_price" value="{{ $totalPrice }}">

                <button type="submit" class="btn btn-success w-100">Thanh toán qua VNPAY</button>
            </form>
        </div>
    </div>
</div>
@endsection
