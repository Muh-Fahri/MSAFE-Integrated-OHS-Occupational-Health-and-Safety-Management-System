<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="index.html" class="logo logo-dark">
                    <span class="logo-sm">
                        <img class="animate__animated animate__backInLeft"
                            src="{{ asset('Minible/HTML/dist') }}/assets/images/logo-sm.png" alt=""
                            height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('Minible/HTML/dist') }}/assets/images/logo-dark.png" alt=""
                            height="20" class="animate__animated animate__backInLeft">
                    </span>
                </a>

                <a href="index.html" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="{{ asset('Minible/HTML/dist') }}/assets/images/logo-sm.png" alt=""
                            height="22" class="animate__animated animate__backInLeft">
                    </span>
                    <span class="logo-lg">
                        <img src="{{ asset('Minible/HTML/dist') }}/assets/images/logo-light.png" alt=""
                            height="20" class="animate__animated animate__backInLeft">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">

            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" id="page-header-search-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="uil-search"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Recipient's username">
                                <div class="input-group-append">
                                    <button class="btn btn-primary" type="submit"><i
                                            class="mdi mdi-magnify"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="uil-minus-path"></i>
                </button>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i class="uil-bell"></i>
                    <span class="badge bg-danger rounded-pill">{{ $notification['count'] }}</span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h5 class="m-0 font-size-16"> Notifications </h5>
                            </div>
                            <div class="col-auto">
                            </div>
                        </div>
                    </div>

                    <div data-simplebar style="max-height: 230px;">
                        @if ($notification['count'] > 0)
                            @foreach ($notification['list'] as $v)
                                <a href="{{ $v->url }}" class="text-dark notification-item">
                                    <div class="d-flex align-items-start">
                                        <div class="flex-shrink-0 me-3">
                                            <div class="avatar-xs">
                                                <span class="avatar-title bg-primary rounded-circle font-size-16">
                                                    <i class="{{ $v->icon }}"></i>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="flex-grow-1">
                                            <h6 class="mb-1">{{ $v->application_name }}</h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">{{ $v->message }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @auth
                        @if (Auth::user()->photo)
                            <img class="rounded-circle header-profile-user"
                                src="{{ asset('uploads/users/' . Auth::user()->photo) }}" alt="{{ Auth::user()->name }}">
                        @else
                            <img class="rounded-circle header-profile-user" src="{{ asset('img/user.png') }}"
                                alt="Default Avatar">
                        @endif
                        <span class="d-none d-xl-inline-block ms-1 fw-medium font-size-15">{{ Auth::user()->name }}</span>
                    @endauth
                    <i class="uil-angle-down d-none d-xl-inline-block font-size-15"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- View Profile -->
                    <a class="dropdown-item" href="{{ route('profile.show') }}"><i
                            class="uil uil-user-circle font-size-18 align-middle text-muted me-1"></i> <span
                            class="align-middle">View Profile</span></a>

                    <!-- Sign Out -->
                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="dropdown-item"
                            style="border: none; background: none; width: 100%; text-align: left;">
                            <i class="uil uil-sign-out-alt font-size-18 align-middle me-1 text-muted"></i>
                            <span class="align-middle">Sign out</span>
                        </button>
                    </form>
                </div>
            </div>

            <!-- <div class="dropdown d-inline-block">
                        <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                            <i class="uil-cog"></i>
                        </button>
                    </div> -->

        </div>
    </div>
</header>
