<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/davidshimjs/qrcodejs/qrcode.min.js"></script>
    <style>
        .custom-brand {
            font-size: 24px;
        }

        main {
            flex: 1;
        }

        body {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .custom-bg {
            background-color: rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body
    style="background-image: url('{{ asset('template/assets/images/bg-themes/3.png') }}'); background-size: cover; background-repeat: no-repeat;">
    <header class="custom-bg text-white py-2">
        <div class="container-fluid ">
            <nav class="navbar navbar-expand-lg navbar-dark ml-5">
                <a class="navbar-brand custom-brand" href="{{ route('home') }}">Project Mini</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ml-auto mr-5">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('home') }}">Short URL</a>
                        </li>
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Chào {{ Auth::user()->name }}
                                </a>
                                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng
                                        xuất</a>
                                </div>
                            </li>
                            <li class="nav-item">
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </li>
                        @endauth
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Đăng nhập</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">Đăng ký</a>
                            </li>
                        @endguest
                    </ul>
                </div>
            </nav>
        </div>
    </header>
    <main>
        @yield('content')
    </main>

    <footer class="custom-bg text-white py-3 text-center">
        <div class="container">
            <p class="mb-0">Copyright &copy; 2023 Mini Project</p>
        </div>
    </footer>
</body>
<!-- Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>

<!-- Latest compiled JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
