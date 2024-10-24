<nav class="navbar navbar-expand navbar-dark blue-gradient">

  <a class="navbar-brand" href="/"><i class="far fa-sticky-note mr-1"></i>Memoφ(..)メモ</a>

  <ul class="navbar-nav ml-auto">

  @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('register') }}">ユーザー登録|ω・)</a>
    </li>
  @endguest

  @guest
    <li class="nav-item">
      <a class="nav-link" href="{{ route('login') }}">ログイン(´・ω・｀)</a>
    </li>
  @endguest

  @auth
    <li class="nav-item">
      <a class="nav-link" href="{{ route('articles.create') }}"><i class="fas fa-pen mr-1"></i>投稿する(｀・ω・´)ゞ</a>
    </li>
  @endauth

  @auth
    <!-- Dropdown -->
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-user-circle"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right dropdown-primary" aria-labelledby="navbarDropdownMenuLink">
        <button class="dropdown-item" type="button" onclick="location.href='{{ route("users.show", ["name" => Auth::user()->name]) }}'">
          マイページ( ..)φメモメモ
        </button>
        <div class="dropdown-divider"></div>
        <button form="logout-button" class="dropdown-item" type="submit">
          ログアウトε≡≡ﾍ( ´Д`)ﾉ
        </button>
      </div>
    </li>
    <form id="logout-button" method="POST" action="{{ route('logout') }}">
      @csrf
    </form>
    <!-- Dropdown -->
     @endauth

  </ul>

</nav>