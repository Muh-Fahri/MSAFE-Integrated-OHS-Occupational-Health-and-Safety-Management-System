<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <!-- LOGO -->
    <div class="navbar-brand-box" style="display: flex; align-items: center; justify-content: center; padding: 10px 0;">
        <a href="{{ url('/') }}" class="logo logo-dark text-center">
            <span class="logo-sm">
                <img src="{{ asset('logo/logo_collapsed_sidebar.png') }}" alt="MDA" height="35">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('logo/logo_expanded_sidebar.png') }}" alt="MDA" height="40">
            </span>
        </a>

        <a href="{{ url('/') }}" class="logo logo-light text-center">
            <span class="logo-sm">
                <img src="{{ asset('logo/logo_collapsed_sidebar.png') }}" alt="MDA" height="35">
            </span>
            <span class="logo-lg">
                <img src="{{ asset('logo/logo_expanded_sidebar.png') }}" alt="MDA" height="40">
            </span>
        </a>
    </div>

    <button type="button" class="btn btn-sm px-3 font-size-16 header-item waves-effect vertical-menu-btn">
        <i class="fa fa-fw fa-bars"></i>
    </button>

    <div data-simplebar class="sidebar-menu-scroll pt-5">

        <!--- Side menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title">Menu</li>

                {{-- Dashboard - Always visible, no permission check --}}
                <li>
                    <a href="{{ route('dashboard') }}">
                        <i class="fas fa-home"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- Menu Management - Check permission for menu_id = 8 --}}
                {{-- @if (\App\Helpers\PermissionHelper::canAccessMenu(8))
                    <li>
                        <a href="{{ route('menus.index') }}">
                            <i class="fas fa-list"></i>
                            <span>Menu Management</span>
                        </a>
                    </li>
                @endif --}}

                {{-- Dynamic menus from database with permission check --}}
                @php
                    $menus = \App\Models\Menu::with('children')->whereNull('parent_id')->orderBy('order')->get();
                @endphp

                @foreach ($menus as $menu)
                    {{-- Skip if user cannot access this menu --}}
                    @if (\App\Helpers\PermissionHelper::canAccessMenu($menu->id))
                        <li>
                            @if ($menu->children->count() > 0)
                                {{-- Parent menu with children --}}
                                @php
                                    // Check if at least one child is accessible
                                    $hasAccessibleChild = false;
                                    foreach ($menu->children as $child) {
                                        if (\App\Helpers\PermissionHelper::canAccessMenu($child->id)) {
                                            $hasAccessibleChild = true;
                                            break;
                                        }
                                    }
                                @endphp

                                @if ($hasAccessibleChild)
                                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                                        <i class="{{ $menu->icon }}"></i>
                                        <span>{{ $menu->menu_name }}</span>
                                    </a>
                                    <ul class="sub-menu" aria-expanded="false">
                                        @foreach ($menu->children as $child)
                                            @if (\App\Helpers\PermissionHelper::canAccessMenu($child->id))
                                                <li>
                                                    <a
                                                        href="{{ $child->url && $child->url != '#' ? route($child->url) : '#' }}">
                                                        {{ $child->menu_name }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            @else
                                {{-- Single menu without children --}}
                                <a href="{{ $menu->url && $menu->url != '#' ? route($menu->url) : '#' }}"
                                    class="waves-effect">
                                    <i class="{{ $menu->icon }}"></i>
                                    <span>{{ $menu->menu_name }}</span>
                                </a>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
