 <header>
        <div class="container">
            <div class="header-content">
                <div class="logo">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="" class="h-16">
                </div>

                <nav class="nav-links">
                    <a href="/">Home</a>
                    <a href="#">Service</a>
                    <a href="#">Blog</a>
                    <a href="#">Contact</a>
                </nav>

                @if (Route::has('login'))
                    <div class="auth-buttons">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-register">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-login">
                                Log in
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-register">
                                    Register
                                </a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>
    </header>
