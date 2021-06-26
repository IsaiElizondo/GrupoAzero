<div class="sidebar" data-color="orange" data-background-color="white" data-image="{{ asset('material') }}/img/sidebar-1.jpg">
    <!--
        Tip 1: You can change the color of the sidebar using: data-color="purple | azure | green | orange | danger"

        Tip 2: you can also add an image using data-image tag
    -->
    <div class="logo">
    <a href="/" class="simple-text logo-normal">
        <img src="{{ asset('img/logo.png') }}" alt="" style="width: 50px;">
        {{-- {{ __('Grupo Azero') }} --}}
    </a>
    </div>
    <div class="sidebar-wrapper">
        <ul class="nav">
            <li class="nav-item{{ $activePage == 'dashboard' ? ' active' : '' }}">
                <a class="nav-link" href="{{ route('home') }}">
                    <i class="material-icons">dashboard</i>
                    <p>{{ __('Dashboard') }}</p>
                </a>
            </li>
            @if ( auth()->user()->role->name != "Cliente" )
                <li class="nav-item{{ $activePage == 'orders' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('orders.index') }}">
                        <i class="material-icons">receipt</i>
                        <p>{{ __('Pedidos') }}</p>
                    </a>
                </li>
            @endif
            {{-- @if ( auth()->user()->role->name != "Flotilla" ) --}}
                <li class="nav-item{{ $activePage == 'follows' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('follows.index') }}">
                        <i class="material-icons">favorite</i>
                        <p>{{ __('Mis Pedidos') }}</p>
                    </a>
                </li>
            {{-- @endif --}}
            <li class="nav-item {{
                (
                    $activePage == 'profile' ||
                    $activePage == 'user-management' ||
                    $activePage == 'roles' ||
                    $activePage == 'departments'
                ) ? ' active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#admin" aria-expanded="true">
                <p>
                <span class="material-icons">tune</span>
                Administración
                <b class="caret"></b>
                </p>
            </a>
            <div class="collapse {{
                (
                    $activePage == 'profile' ||
                    $activePage == 'user-management' ||
                    $activePage == 'roles' ||
                    $activePage == 'departments'
                ) ? ' show' : '' }}" id="admin">
                <ul class="nav">
                <li class="nav-item{{ $activePage == 'profile' ? ' active' : '' }}">
                    <a class="nav-link" href="{{ route('profile.edit') }}">
                    <span class="sidebar-mini"> PU </span>
                    <span class="sidebar-normal">{{ __('Perfil de usuario') }} </span>
                    </a>
                </li>
                @if ( auth()->user()->role->name == "Administrador")
                    <li class="nav-item{{ $activePage == 'user-management' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('users.index') }}">
                        <span class="sidebar-mini"> US </span>
                        <span class="sidebar-normal"> {{ __('Usuarios') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'roles' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('roles.index') }}">
                            <span class="sidebar-mini"> RL </span>
                            <span class="sidebar-normal"> {{ __('Roles') }} </span>
                        </a>
                    </li>
                    <li class="nav-item{{ $activePage == 'departments' ? ' active' : '' }}">
                        <a class="nav-link" href="{{ route('departments.index') }}">
                            <span class="sidebar-mini"> DP </span>
                            <span class="sidebar-normal"> {{ __('Departamentos') }} </span>
                        </a>
                    </li>
                @endif
                </ul>
            </div>
            </li>
            {{-- <li class="nav-item{{ $activePage == 'table' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('table') }}">
                <i class="material-icons">content_paste</i>
                <p>{{ __('Table List') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'typography' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('typography') }}">
                <i class="material-icons">library_books</i>
                <p>{{ __('Typography') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'icons' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('icons') }}">
                <i class="material-icons">bubble_chart</i>
                <p>{{ __('Icons') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'map' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('map') }}">
                <i class="material-icons">location_ons</i>
                <p>{{ __('Maps') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'notifications' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('notifications') }}">
                <i class="material-icons">notifications</i>
                <p>{{ __('Notifications') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item{{ $activePage == 'language' ? ' active' : '' }}">
            <a class="nav-link" href="{{ route('language') }}">
                <i class="material-icons">language</i>
                <p>{{ __('RTL Support') }}</p>
            </a>
            </li> --}}
            {{-- <li class="nav-item active-pro{{ $activePage == 'upgrade' ? ' active' : '' }} bg-danger">
            <a class="nav-link text-white" href="{{ route('upgrade') }}">
                <i class="material-icons">unarchive</i>
                <p>{{ __('Upgrade to PRO') }}</p>
            </a>
            </li> --}}
        </ul>
    </div>
</div>
