<x-guest-layout>
    <!-- Logo & Title -->
    <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
        <div class="logo-badge">
            <i class="bi bi-fire"></i>
        </div>
        <h2 class="brand-title">Resep<span>Kita</span></h2>
        <p class="brand-sub">Buat akun baru Anda</p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('register') }}" class="mt-4" data-aos="fade-up" data-aos-delay="400" novalidate>
        @csrf

        <!-- Name -->
        <x-auth-input 
            id="name" 
            name="name" 
            :value="old('name')" 
            placeholder="Nama Lengkap Anda" 
            icon="bi-person" 
            autocomplete="name" 
            required 
            autofocus 
        />

        <!-- Username -->
        <x-auth-input 
            id="username" 
            name="username" 
            :value="old('username')" 
            placeholder="Username Pilihan" 
            icon="bi-person-badge" 
            autocomplete="username" 
            required 
        />

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
        />

        <!-- Password -->
        <x-auth-input 
            id="password" 
            name="password" 
            type="password" 
            placeholder="Password Baru" 
            icon="bi-lock" 
            autocomplete="new-password" 
            required 
            togglePassword="true" 
        />

        <!-- Confirm Password -->
        <x-auth-input 
            id="password_confirmation" 
            name="password_confirmation" 
            type="password" 
            placeholder="Konfirmasi Password" 
            icon="bi-lock-fill" 
            autocomplete="new-password" 
            required 
            togglePassword="true" 
        />

        <!-- Submit Button -->
        <div class="mt-4">
            <x-auth-button id="registerBtn" text="Daftar Sekarang" />
        </div>
    </form>

    <!-- Login Link -->
    <div class="register-box" data-aos="fade-up" data-aos-delay="500">
        Sudah punya akun?
        <a href="{{ route('login') }}">Masuk Sekarang →</a>
    </div>
</x-guest-layout>
