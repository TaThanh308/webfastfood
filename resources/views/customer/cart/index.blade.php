@extends('layouts.user')

@section('content')
<div class="container">
    <h2>Giỏ Hàng</h2>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cart->isEmpty())
        <p>Giỏ hàng của bạn đang trống.</p>
    @else
        <table class="table">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Tổng</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cart as $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td><img src="{{ asset('storage/' . $item->image) }}" width="50"></td>
                    <td>
                        {{ number_format($item->final_price, 0, ',', '.') }} đ
                    </td>
                    <td>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1">
                            <button type="submit">Cập nhật</button>
                        </form>
                    </td>
                    <td>
                        {{ number_format($item->final_price * $item->quantity, 0, ',', '.') }} đ
                    </td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <a href="#" class="btn btn-primary">Thanh toán</a>
    @endif
</div>
@endsection
