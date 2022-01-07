<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- Left navbar links -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
    </li>
    <li class="nav-item d-none d-sm-inline-block">
      <a href="{{ route('home') }}" class="nav-link">{{__('Home')}}</a>
    </li>
  </ul>
  <!-- Right navbar links -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications Dropdown Menu -->
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="far fa-address-card"></i>
        <!-- <span class="badge badge-warning navbar-badge">15</span> -->
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <a href="{{ route('account.resetMyPassword') }}" class="dropdown-item">
          <i class="fas fa-user-circle mr-2"></i> {{__('Account Management')}}
        </a>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>
        <div class="dropdown-divider"></div>
        <a href="{{route('logout')}}" class="dropdown-item" 
        onclick="event.preventDefault();document.getElementById('logout-form').submit();">
          <i class="fas fa-sign-out-alt mr-2" style="color: red"></i> {{__('Logout')}}
        </a>
      </div>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link" data-toggle="dropdown" href="#">
        <i class="fas fa-language"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        <div class="dropdown-divider"></div>
        <a href="{{ route('home.changeLanguage', ['vi']) }}" class="dropdown-item">
          <img src="{{ asset('dist/img/vi.png') }}" style="width: 60px; height: 30px"> {{__('Vietnamese')}}
        </a>
        <div class="dropdown-divider"></div>
        <a href="{{ route('home.changeLanguage', ['en']) }}" class="dropdown-item">
          <img src="{{ asset('dist/img/en.png') }}" style="width: 60px; height: 30px"> {{__('English')}}
        </a>
        <a href="{{ route('home.changeLanguage', ['ko']) }}" class="dropdown-item">
          <img src="{{ asset('dist/img/korean.png') }}" style="width: 60px; height: 30px"> {{__('Korean')}}
        </a>
      </div>
    </li>
  </ul>
</nav>