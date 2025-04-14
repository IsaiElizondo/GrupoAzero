<!-- Navbar -->
{{-- <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top text-white"> --}}
<nav class="navbar navbar-expand-lg bg-primary navbar-absolute fixed-top text-white">
  <div class="container">
    <div class="navbar-wrapper">
      {{-- <a class="navbar-brand" href="{{ route('home') }}">{{ $title }}</a> --}}
      <a class="navbar-brand" href="{{ route('welcome') }}"><img src="{{ asset('img/logo.png') }}" alt="" style="width: 50px; margin-top: -10px;"></a>
    </div>
    <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only">Toggle navigation</span>
      <span class="navbar-toggler-icon icon-bar" style="background-color: white"></span>
      <span class="navbar-toggler-icon icon-bar" style="background-color: white"></span>
      <span class="navbar-toggler-icon icon-bar" style="background-color: white"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end">
      <ul class="navbar-nav">

        @auth
        <li class="nav-item">
          <a href="{{ url('pedidos2/index') }}" class="nav-link">
            <i class="material-icons">dashboard</i> {{ __('Pedidos') }}
          </a>
        </li>
        @endauth

        @guest

        <!--
            <li class="nav-item{{ $activePage == 'register' ? ' active' : '' }}">
                <a href="{{ route('register') }}" class="nav-link">
                    <i class="material-icons">person_add</i> {{ __('Registro') }}
                </a>
            </li>
        -->
                <li class="nav-item{{ $activePage == 'login' ? ' active' : '' }}">
                <a href="{{ route('login') }}" class="nav-link">
                    <i class="material-icons">fingerprint</i> {{ __('Login') }}
                </a>
            </li>
        @endguest
        
        
        @auth
        <li class="nav-item ">
          <a href="{{ route('profile.edit') }}" class="nav-link">
            <i class="material-icons">face</i> {{ __('Perfil') }}
          </a>
        </li>
        @endauth

      </ul>
    </div>
  </div>
</nav>
<!-- End Navbar -->
