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
            <a class="navbar-brand" href="{{ route('home') }}">
              <img src="{{ asset('storage/images/logo.jpg') }}" alt="Bán đồ ăn nhanh Logo" width="70" height="auto" class="d-inline-block align-top">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="bestseller.html"><i class="fas fa-star"></i> Bestseller</a></li>
                    <li class="nav-item"><a class="nav-link"href="{{ route('customer.cart.index') }}"><i class="fas fa-cart-plus"></i> Giỏ hàng của bạn</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('orders.my') }}"><i class="fas fa-store"></i> Lịch sửa mua hàng</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.discounts.index') }}"><i class="fas fa-tag"></i> Khuyến Mãi</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('customer.products.index') }}"><i class="fas fa-store"></i> Cửa Hàng</a></li>
                </ul>
                
                <div class="d-flex align-items-center">
    @auth
        <div class="dropdown">
            <button class="btn btn-light dropdown-toggle" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                👤 Xin chào, {{ Auth::user()->name }}
            </button>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
            @if(auth()->user()->social_provider == null)
    <li>
        <a class="dropdown-item" href="{{ route('password.change.form') }}">
            <i class="fas fa-key"></i> Đổi mật khẩu
        </a>
    </li>
@endif

                <li>
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    @else
        <button class="btn btn-light me-2" onclick="location.href='{{ route('login') }}'">
            <i class="fas fa-user"></i> Đăng nhập
        </button>
    @endauth
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
                        <li>
                            <a href="https://www.facebook.com/profile.php?id=100009808607150" target="_blank">
                                <i class="fab fa-facebook"></i> Facebook
                            </a>
                        </li>
                        <li><a href="https://www.instagram.com/hungdoll13?fbclid=IwY2xjawIznxNleHRuA2FlbQIxMAABHbBsjAjeax6PB0UFZVO8lFQwY4AV1nR2z2lMo3eVkPmYdBk9TsBIXHwaeg_aem_BYc0483jprPi-iurOpB7wQ" target="_blank"><i class="fab fa-instagram"></i> Instagram</a></li>
                        <li><a href="https://www.tiktok.com/@hung13403/video/7164184511691885851?_r=1&_d=secCgYIASAHKAESPgo8U3DLZzIejnPiw%2BbnHQsyTgG51%2FXDKoua7lTBNKQUdd3pdf7MCj%2BUaH23ZTzCV2EmqnLe9jMsrVsylXKYGgA%3D&checksum=a6d2651b2c84928ce594fc0eb7743a6c83d8956a0366ca25d69976345285665b&cover_exp=v1&link_reflow_popup_iteration_sharer=%7B%22click_empty_to_play%22%3A1%2C%22dynamic_cover%22%3A1%2C%22profile_clickable%22%3A1%2C%22follow_to_play_duration%22%3A-1%7D&mid=7151087722424650522&preview_pb=0&region=VN&sec_user_id=MS4wLjABAAAA__-qFMBrcN7AA-dNnNRQWKax5bubtw6yGbOWTdU3_j3QnmJOfZwsWROCSSOcNmbr&share_app_id=1180&share_item_id=7164184511691885851&share_link_id=F5010560-85F9-41D6-BCBB-091313ACCE64&sharer_language=vi&social_share_type=0&source=h5_t&timestamp=1741079071&tt_from=copy&u_code=d23jf6c1l3df55&ug_btm=b8727%2Cb2878&user_id=6599975015322861569&utm_campaign=client_share&utm_medium=ios&utm_source=copy" target="_blank"><i class="fab fa-tiktok"></i> Tiktok</a></li>
                        <li><a href="https://youtu.be/GMt-saaxhCE?si=dBebYvTCyHNlKmXF" target="_blank"><i class="fab fa-youtube"></i> youtube</a></li>
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
