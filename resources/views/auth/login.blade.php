<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-extrabold text-brand-ink uppercase tracking-wider">Login</h2>
        <p class="text-xs text-gray-500 mt-1">Masuk untuk mengelola menu dan memesan kopi</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="/login" class="space-y-5">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-brand-ink font-semibold mb-1" />
            <x-text-input id="email" class="block w-full px-4 py-3 border-gray-200" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="admin@example.com atau user@example.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-brand-ink font-semibold mb-1" />
            <x-text-input id="password" class="block w-full px-4 py-3 border-gray-200"
                            type="password"
                            name="password"
                            required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded-lg border-brand-line text-brand-brown shadow-sm focus:ring-brand-teal" name="remember">
                <span class="ms-2 text-sm text-slate-600">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-semibold text-brand-ink hover:text-brand-brown transition" href="/forgot-password">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-8 text-center text-sm text-brand-muted">
        Belum punya akun?
        <a href="/register" class="font-bold text-brand-ink hover:underline">
            Daftar Sekarang
        </a>
    </div>
</x-guest-layout>
