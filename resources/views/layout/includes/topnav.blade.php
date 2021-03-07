<nav class="navbar navbar-expand-sm bg-info navbar-dark" id="app-nav">
  <!-- Brand/logo -->
  <a class="navbar-brand" href="{{ route('app.index') }}">
    <img src="{{ asset('public/images/logox.png') }}" alt="logo" style="width:40px;">
    <span style="color:#fff; font-weight:700; font-size: 24px;">Client Box</span>
  </a>
  <ul class="nav navbar-nav mr-auto"></ul> <!-- Add this one -->
  <!-- Links -->
  <ul class="nav navbar-nav navbar-right">
    @if(!Auth::check())
    <li class="nav-item active">
      <a class="nav-link" href="javascript:void(0);" id="loginBoxBtn"><i class="fas fa-sign-in-alt"></i> Login</a>
    </li>
    @endif
    @if(Auth::check())
      <li class="nav-item @if(isset($pageName) && $pageName == 'MyAccount') active @endif">
        <a class="nav-link" href="{{ route('myAccount') }}"><i class="fas fa-user"></i> My Account</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('logoutUserAccount') }}"><i class="fas fa-power-off"></i> Logout</a>
      </li>
    @endif
  </ul>
</nav>