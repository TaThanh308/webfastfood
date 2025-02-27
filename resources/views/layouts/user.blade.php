<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bán đồ ăn nhanh</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .navbar {
            background-color: #f8d9e0;
        }
        .navbar {
            padding: 15px 20px; /* Tăng khoảng cách trên dưới */
            font-size: 18px; /* Tăng kích thước chữ */
        }

        .navbar .logo {
            width: 90px; /* Tăng kích thước logo */
        }

        .navbar-nav .nav-link {
            font-size: 18px; /* Tăng kích thước chữ của menu */
            padding: 10px 15px; /* Tăng kích thước vùng nhấn */
        }

        .navbar-toggler {
            font-size: 22px; /* Làm to icon menu trên mobile */
        }

        .footer {
            background-color: #f4e1d2;
            padding: 40px 20px;
        }

        .footer a {
            text-decoration: none;
            color: #333;
        }

        .footer a:hover {
            color: #f44336;
        }

        .footer-copyright {
            text-align: center;
            background-color: #333;
            color: whitesmoke;
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container">
            <a class="navbar-brand" href="#">
              <img src="{{ asset('storage/images/logo.jpg') }}" alt="Bán đồ ăn nhanh Logo" width="70" height="auto" class="d-inline-block align-top">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="bestseller.html"><i class="fas fa-star"></i> Bestseller</a></li>
                    <li class="nav-item"><a class="nav-link" href="order/index.html"><i class="fas fa-cart-plus"></i> Đặt Hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="khuyenmai.html"><i class="fas fa-tag"></i> Khuyến Mãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fas fa-store"></i> Cửa Hàng</a></li>
                </ul>
                <div class="d-flex">
                    <button class="btn btn-light me-2" onclick="location.href='{{ route('login') }}'">
                        <i class="fas fa-user"></i>
                    </button>
                    <button class="btn btn-light me-2"><i class="fas fa-bell"></i></button>
                    <a href="order/history.html" class="btn btn-light me-2"><i class="fas fa-shopping-cart"></i></a>
                    <button class="btn btn-danger">Tải App</button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div class="container my-4">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h4>Thông Tin</h4>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-newspaper"></i> Tin tức</a></li>
                        <li><a href="khuyenmai.html"><i class="fas fa-gift"></i> Khuyến mãi</a></li>
                        <li><a href="#"><i class="fas fa-users"></i> Tuyển dụng</a></li>
                        <li><a href="#"><i class="fas fa-handshake"></i> Nhượng quyền</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4>Hỗ Trợ</h4>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fas fa-cogs"></i> Điều khoản sử dụng</a></li>
                        <li><a href="#"><i class="fas fa-shield-alt"></i> Chính sách bảo mật</a></li>
                        <li><a href="#"><i class="fas fa-truck"></i> Chính sách giao hàng</a></li>
                        <li><a href="#"><i class="fas fa-headset"></i> Chăm sóc khách hàng</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h4>Theo Dõi</h4>
                    <ul class="list-unstyled">
                        <li><a href="#"><i class="fab fa-facebook"></i> Facebook</a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i> Instagram</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <div class="footer-copyright">
        <p>© 2025 All Rights Reserved | Site by LDCC</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
