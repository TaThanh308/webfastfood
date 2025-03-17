@extends('layouts.user')

@section('title', 'Thanh toán')

@section('content')
<div class="container mt-5">
    <h2 class="text-center mb-4">Thông tin thanh toán</h2>
    <div class="row justify-content-center">
        <div class="col-md-6">
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
