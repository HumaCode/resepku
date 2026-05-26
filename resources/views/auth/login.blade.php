<x-guest-layout>
    <!-- Logo & Title -->
    <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
        <div class="logo-badge">
            <i class="bi bi-fire"></i>
        </div>
        <h2 class="brand-title">Resep<span>Kita</span></h2>
        <p class="brand-sub">Masuk ke akun kamu</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Social Login -->
    <div class="mt-3" data-aos="fade-up" data-aos-delay="300">
        <a href="#" class="btn-social mb-2">
            <svg width="20" height="20" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/><path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l3.66-2.84z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/></svg>
            Lanjutkan dengan Google
        </a>
    </div>

    <div class="divider-label" data-aos="fade-up" data-aos-delay="350">atau masuk dengan email</div>

    <!-- Form -->
    <form id="loginForm" method="POST" action="{{ route('login') }}" data-aos="fade-up" data-aos-delay="400" novalidate>
        @csrf

        <!-- Username -->
        <div class="mb-3 text-start">
            <div class="input-wrap">
                <i class="bi bi-person icon-left"></i>
                <input type="text" id="username" name="username" value="{{ old('username') }}" placeholder="Username kamu" autocomplete="username" required autofocus />
            </div>
            <div class="error-feedback text-danger small mt-1" id="error-username">
                @error('username') {{ $message }} @enderror
            </div>
        </div>

        <!-- Password -->
        <div class="mb-3 text-start">
            <div class="input-wrap">
                <i class="bi bi-lock icon-left"></i>
                <input type="password" id="password" name="password" placeholder="Password" autocomplete="current-password" required />
                <button type="button" class="toggle-pw" id="togglePw">
                    <i class="bi bi-eye" id="eyeIcon"></i>
                </button>
            </div>
            <div class="error-feedback text-danger small mt-1" id="error-password">
                @error('password') {{ $message }} @enderror
            </div>
        </div>

        <!-- Remember Me / Forgot Password -->
        <div class="d-flex align-items-center justify-content-between mb-3" data-aos="fade-up" data-aos-delay="450">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember" name="remember"/>
                <label class="form-check-label" for="remember">Ingat saya</label>
            </div>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="link-forgot">Lupa password?</a>
            @endif
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn-login" id="loginBtn">
            <div class="spinner" id="spinner"></div>
            <span class="btn-text">Masuk Sekarang</span>
        </button>
    </form>

    <!-- Register Link -->
    @if (Route::has('register'))
        <div class="register-box" data-aos="fade-up" data-aos-delay="500">
            Belum punya akun?
            <a href="{{ route('register') }}">Daftar Sekarang →</a>
        </div>
    @endif
</x-guest-layout>
