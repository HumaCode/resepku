<x-guest-layout>
    <!-- Logo & Title -->
    <div class="text-center" data-aos="zoom-in" data-aos-delay="200">
        <div class="logo-badge">
            <i class="bi bi-fire"></i>
        </div>
        <h2 class="brand-title">Resep<span>Kita</span></h2>
        <p class="brand-sub">Atur Ulang Password</p>
    </div>

    <!-- Form -->
    <form id="resetPasswordForm" method="POST" action="{{ route('password.store') }}" class="mt-4" data-aos="fade-up" data-aos-delay="400" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <x-auth-input 
            id="email" 
            name="email" 
            type="email" 
            :value="old('email', $request->email)" 
            placeholder="Alamat Email Anda" 
            icon="bi-envelope" 
            autocomplete="email" 
            required 
            readonly 
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
            autofocus 
        />

        <!-- Confirm Password -->
        <x-auth-input 
            id="password_confirmation" 
            name="password_confirmation" 
            type="password" 
            placeholder="Konfirmasi Password Baru" 
            icon="bi-lock-fill" 
            autocomplete="new-password" 
            required 
            togglePassword="true" 
        />

        <!-- Submit Button -->
        <div class="mt-4">
            <x-auth-button id="resetPasswordBtn" text="Reset Password" />
        </div>
    </form>
</x-guest-layout>
