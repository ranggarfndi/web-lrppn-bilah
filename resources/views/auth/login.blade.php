{{-- File: resources/views/auth/login.blade.php --}}

<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- Judul Form --}}
    <h2 class="text-2xl font-bold text-center text-gray-900 mb-6">
        Login ke Akun Anda
    </h2>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox"
                    class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Ingat saya') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-between mt-6">
            {{-- Link Lupa Password --}}
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors duration-150"
                    href="{{ route('password.request') }}">
                    {{ __('Lupa password?') }}
                </a>
            @endif

            {{-- Tombol Login Utama --}}
            <x-primary-button class="ms-3">
                {{ __('Log In') }}
            </x-primary-button>
        </div>

        {{-- Link ke Halaman Register --}}
        <div class="text-center mt-8 border-t pt-6">
            {{-- <p class="text-sm text-gray-600">
                Belum punya akun?
                <a class="underline text-indigo-600 hover:text-indigo-800 font-medium" href="{{ route('register') }}">
                    Daftar di sini
                </a>
            </p> --}}
        </div>
    </form>
</x-guest-layout>
