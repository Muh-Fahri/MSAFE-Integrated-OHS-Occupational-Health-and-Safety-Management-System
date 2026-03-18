<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Lock Screen | {{ config('app.name') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('Minible/HTML/dist/assets/images/favicon.ico') }}">

    <!-- Bootstrap Css -->
    <link href="{{ asset('Minible/HTML/dist/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('Minible/HTML/dist/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('Minible/HTML/dist/assets/css/app.min.css') }}" id="app-style" rel="stylesheet"
        type="text/css" />
</head>

<body class="authentication-bg">
    <div class="account-pages my-5 pt-sm-5">
        <div class="container">
            <div class="row justify-content-center">

                <div class="col-md-8 col-lg-6 col-xl-5">
                    <div>
                        <a href="{{ route('lockscreen.show') }}" class="mb-5 d-block auth-logo">
                            <img src="{{ asset('Minible/HTML/dist/assets/images/logo-dark.png') }}" alt=""
                                height="22" class="logo logo-dark">
                            <img src="{{ asset('Minible/HTML/dist/assets/images/logo-light.png') }}" alt=""
                                height="22" class="logo logo-light">
                        </a>
                        <div class="card">

                            <div class="card-body p-4">

                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Lock Screen</h5>
                                    <p class="text-muted">Enter your password to unlock the screen!</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <div class="user-thumb text-center mb-4">
                                        @if ($user->photo)
                                            <img src="{{ asset('uploads/users/' . $user->photo) }}"
                                                class="rounded-circle img-thumbnail avatar-lg" alt="{{ $user->name }}">
                                        @else
                                            <img src="{{ asset('Minible/HTML/dist/assets/images/users/avatar-4.jpg') }}"
                                                class="rounded-circle img-thumbnail avatar-lg" alt="{{ $user->name }}">
                                        @endif
                                        <h5 class="font-size-15 mt-3">{{ $user->name }}</h5>
                                    </div>
                                    <form action="{{ route('lockscreen.unlock') }}" method="POST">
                                        @csrf
                                        <div class="mb-3">
                                            <label class="form-label" for="userpassword">Password</label>
                                            <input type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                id="userpassword" name="password" placeholder="Enter password"
                                                autofocus required>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button class="btn btn-primary w-sm waves-effect waves-light"
                                                type="submit">Unlock</button>
                                        </div>
                                    </form>
                                    
                                    <div class="mt-4 text-center">
                                        <p class="mb-0">Not you?
                                            <form action="{{ route('lockscreen.logout') }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0 fw-medium text-primary" style="text-decoration: none;">
                                                    Sign Out
                                                </button>
                                            </form>
                                        </p>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>© <script>
                                    document.write(new Date().getFullYear())
                                </script> {{ config('app.name') }}. Crafted with <i
                                    class="mdi mdi-heart text-danger"></i> by Your Team</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JAVASCRIPT -->
    <script src="{{ asset('Minible/HTML/dist/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/node-waves/waves.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/waypoints/lib/jquery.waypoints.min.js') }}"></script>
    <script src="{{ asset('Minible/HTML/dist/assets/libs/jquery.counterup/jquery.counterup.min.js') }}"></script>

</body>

</html>
