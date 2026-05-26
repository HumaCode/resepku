<x-guest-layout>
    <!-- Logo & Title -->
    <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
        <div class="logo-badge">
            <i class="bi bi-fire"></i>
        </div>
        <h2 class="brand-title">Resep<span>Kita</span></h2>
        <p class="brand-sub">Lupa Password Akun</p>
    </div>

    <!-- Description Text -->
    <div class="brand-sub text-center my-3 px-2" style="font-size: 0.88rem; line-height: 1.5; color: var(--muted);" data-aos="fade-up" data-aos-delay="300">
        Lupa password? Jangan khawatir. Masukkan alamat email Anda di bawah ini, dan kami akan mengirimkan tautan untuk mengatur ulang password baru Anda.
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4 text-success small text-center" :status="session('status')" />

    <!-- Form -->
    <form id="forgotPasswordForm" method="POST" action="{{ route('password.email') }}" class="mt-3" data-aos="fade-up" data-aos-delay="400" novalidate>
        @csrf

        <!-- Email Address -->
        <x-auth-input 
            id="email" 
            name="email" 
            type="email" 
            :value="old('email')" 
            placeholder="Alamat Email Anda" 
            icon="bi-envelope" 
            autocomplete="email" 
            required 
            autofocus 
        />

        <!-- Submit Button -->
        <div class="mt-4">
            <x-auth-button id="forgotPasswordBtn" text="Kirim Link Reset Password" />
        </div>
    </form>

    <!-- Back to Login Link -->
    <div class="register-box" data-aos="fade-up" data-aos-delay="500">
        Kembali ke halaman
        <a href="{{ route('login') }}">Masuk Sekarang</a>
    </div>
</x-guest-layout>
