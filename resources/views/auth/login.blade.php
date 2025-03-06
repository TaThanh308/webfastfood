@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="login-container d-flex bg-white rounded shadow-lg overflow-hidden">
        <!-- Form Đăng Nhập -->
        <div class="p-4 d-flex flex-column justify-content-center" style="width: 350px; height: 500px;">
            <h2 class="text-center mb-3">Đăng nhập</h2>

            @if (session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('login.submit') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="email" name="email" class="form-control rounded-3" placeholder="Nhập email của bạn" required>
                </div>
                <div class="mb-3">
                    <input type="password" name="password" class="form-control rounded-3" placeholder="Nhập mật khẩu của bạn" required>
                </div>
                <button type="submit" class="btn btn-danger w-100 rounded-pill">Đăng nhập</button>
            </form>

            <div class="text-center my-3 text-muted">Hoặc đăng nhập bằng:</div>
            <div class="d-flex justify-content-between">
    <a href="{{ route('social.login', 'facebook') }}" class="btn btn-primary w-50 me-2 d-flex align-items-center justify-content-center">
        <i class="fab fa-facebook-f me-2"></i> Facebook
    </a>
    <a href="{{ route('social.login', 'google') }}" class="btn btn-danger w-50 d-flex align-items-center justify-content-center">
        <i class="fab fa-google me-2"></i> Google
    </a>
</div>

            <div class="text-center mt-3">
                Bạn chưa có tài khoản? <a href="{{ route('register') }}" class="text-danger">Đăng ký ngay</a>
            </div>

        </div>

        <!-- Hình Ảnh -->
        <div class="promo d-flex align-items-center justify-content-center text-white text-center" 
             style="background: url('{{ asset('storage/images/logo.jpg') }}') no-repeat center; background-size: cover; width: 300px; height: 500px;">
        </div>
    </div>
</div>
@endsection
