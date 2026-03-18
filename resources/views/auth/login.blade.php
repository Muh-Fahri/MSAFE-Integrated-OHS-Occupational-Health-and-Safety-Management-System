<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Login | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="icon" href="{{ asset('favicon.ico') }}">

    <link href="{{ asset('Minible/HTML/dist/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link href="{{ asset('Minible/HTML/dist/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('Minible/HTML/dist/assets/css/app.min.css') }}" id="app-style" rel="stylesheet"
        type="text/css" />

    <style>
        /* Memastikan container mengambil tinggi penuh tanpa scroll */
        body,
        html {
            height: 100%;
            margin: 0;
        }

        .auth-full-height {
            height: 100vh;
        }

        /* Memastikan gambar benar-benar di tengah */
        .img-cover {
            width: 100%;
            height: 100%;
            object-fit: cover;
            object-position: center;
            /* Menjaga fokus gambar tetap di tengah */
        }
    </style>
</head>

<body class="authentication-bg" style="overflow: hidden;">
    <div class="container-fluid p-0">
        <div class="row g-0 auth-full-height">

            {{-- Sisi Kiri: Gambar Full (50%) --}}
            <div class="col-md-6 d-none d-md-block">
                <img src="{{ asset('background/ourPorto.png') }}" alt="Background" class="img-cover">
            </div>

            {{-- Sisi Kanan: Form Login (50%) --}}
            <div class="col-md-6 d-flex align-items-center justify-content-center bg-white p-4">
                {{-- max-width dinaikkan agar form tidak terlalu kurus di layar 50:50 --}}
                <div class="auth-card border-0 shadow-none w-100" style="max-width: 60%;">
                    <div class="auth-header mb-4 text-center">
                        <h1 class="h3 animate__animated animate__rubberBand">
                            <b>Welcome Back To MDA Safe!</b>
                        </h1>
                        <p class="text-muted animate__animated animate__fadeIn animate__delay-1s">
                            Sign in your account
                        </p>
                    </div>

                    <div class="auth-body">
                        <form action="{{ route('login') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label" for="username">Username</label>
                                <input type="text"
                                    class="form-control rounded-pill @error('username') is-invalid @enderror"
                                    id="username" name="username" placeholder="Ex: Jhon Doe"
                                    value="{{ old('username') }}" autofocus required>
                                @error('username')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label" for="userpassword">Password</label>
                                <input type="password"
                                    class="form-control rounded-pill @error('password') is-invalid @enderror"
                                    id="userpassword" name="password" placeholder="Enter password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mt-4">
                                <button class="btn rounded-pill w-100  fw-bold text-white waves-light"
                                    style="background-color: #B0BC3F" type="submit">
                                    LogIn
                                </button>
                            </div>

                            <div class="mt-4 text-center">
                                <div class="d-flex align-items-center my-3">
                                    <div class="flex-grow-1 border-top"></div>
                                    <span class="mx-3 text-secondary font-size-14 fw-bold">For MDA Employee <br> login
                                        with</span>
                                    <div class="flex-grow-1 border-top"></div>
                                </div>

                                <a href="{{ url('/auth/microsoft') }}"
                                    class="btn btn-dark rounded-pill w-100 d-flex align-items-center justify-content-center px-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                        viewBox="0 0 21 21" class="me-2">
                                        <path fill="#f25022" d="M0 0h10v10H0z" />
                                        <path fill="#7fba00" d="M11 0h10v10H11z" />
                                        <path fill="#00a4ef" d="M0 11h10v10H0z" />
                                        <path fill="#ffb900" d="M11 11h10v10H11z" />
                                    </svg>
                                    <span>Microsoft</span>
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="{{ asset('Minible/HTML/dist/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/node-waves/waves.min.js') }}"></script>
</body>

</html>
