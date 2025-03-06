@extends('layouts.user')

@section('content')
<div class="container d-flex justify-content-center align-items-center mt-5">
    <div class="registration-container d-flex bg-white rounded shadow-lg overflow-hidden">
        <div class="registration-form p-4">
            <h2 class="text-center mb-3">Đăng ký tài khoản mới</h2>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('register.submit') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <input type="text" name="name" class="form-control form-control-sm" placeholder="Nhập tên của bạn" required>
                </div>

                <div class="mb-3">
                    <input type="email" name="email" class="form-control form-control-sm" placeholder="Nhập email của bạn" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-sm" placeholder="Nhập mật khẩu của bạn" required>
                </div>

                <div class="mb-3">
                    <input type="password" name="password_confirmation" class="form-control form-control-sm" placeholder="Nhập lại mật khẩu của bạn" required>
                </div>

                <button type="submit" class="btn btn-danger w-100">Đăng ký</button>
            </form>
            <div class="text-center my-3 text-muted">Hoặc đăng ký bằng:</div>
            <div class="d-flex justify-content-between">
                <a href="#" class="btn btn-primary w-50 me-2 d-flex align-items-center justify-content-center">
                    <i class="fab fa-facebook-f me-2"></i> Facebook
                </a>
                <a href="#" class="btn btn-danger w-50 d-flex align-items-center justify-content-center">
                    <i class="fab fa-google me-2"></i> Google
                </a>
            </div>

            <div class="text-center mt-3">
                <a href="{{ route('login') }}" class="text-secondary">Đã có tài khoản? Đăng nhập</a>
            </div>
        </div>

        <div class="promo d-flex justify-content-center align-items-center text-white text-center" 
             style="background: url('{{ asset('storage/images/logo.jpg') }}') no-repeat center; background-size: cover; width: 300px;">
        </div>
    </div>
</div>
@endsection
