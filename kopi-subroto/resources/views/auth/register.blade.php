<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-extrabold text-slate-800 uppercase tracking-wider">Daftar Akun</h2>
        <p class="text-xs text-gray-500 mt-1">Buat akun untuk mulai memesan kopi premium</p>
    </div>

    <form method="POST" action="/register" class="space-y-5">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="name" class="block w-full px-4 py-3 border-gray-200" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama Anda" />
            <x-input-error :messages="$errors->get('name')" class="mt-1" />
        </div>

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="email" class="block w-full px-4 py-3 border-gray-200" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-1" />
        </div>

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="password" class="block w-full px-4 py-3 border-gray-200"
                            type="password"
                            name="password"
                            required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-1" />
        </div>

        <!-- Confirm Password -->
        <div>
            <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-700 font-semibold mb-1" />
            <x-text-input id="password_confirmation" class="block w-full px-4 py-3 border-gray-200"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
        </div>

        <div class="pt-2">
            <x-primary-button class="w-full">
                {{ __('Daftar Sekarang') }}
            </x-primary-button>
        </div>
    </form>

    <div class="mt-8 text-center text-sm text-slate-600">
        Sudah punya akun?
        <a href="/login" class="font-bold text-brand-ink hover:underline">
            Login di Sini
        </a>
    </div>
</x-guest-layout>
