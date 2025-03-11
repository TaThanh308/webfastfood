@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="login-container bg-white rounded shadow-lg p-4" style="width: 400px;">
        <h2 class="text-center mb-3">Quên mật khẩu</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form action="{{ route('password.email') }}" method="POST">
            @csrf
            <div class="mb-3">
                <input type="email" name="email" class="form-control rounded-3" placeholder="Nhập email của bạn" required>
            </div>
            <button type="submit" class="btn btn-primary w-100 rounded-pill">Gửi liên kết đặt lại</button>
        </form>

        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-muted">Quay lại đăng nhập</a>
        </div>
    </div>
</div>
@endsection
