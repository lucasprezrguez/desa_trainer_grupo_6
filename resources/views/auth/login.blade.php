<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        @session('status')
            <div class="mb-4 font-medium text-sm text-green-600">
                {{ $value }}
            </div>
        @endsession

        <h2 class="text-3xl font-semibold mb-6">{{ __('Iniciar sesión') }}</h2>

        <form method="POST" action="{{ route('login') }}" class="m-0">
            @csrf

            <div class="relative w-full">
                <x-input id="email" type="email" name="email" :value="old('email')" required autofocus autocomplete="username">
                    <x-label for="email">Correo Electrónico</x-label>
                </x-input>
            </div>
            
            <div class="relative w-full mt-4">
                <x-input id="password" type="password" name="password" required autocomplete="current-password">
                    <x-label for="password">Contraseña</x-label>
                </x-input>
            </div>
            
            <div class="flex justify-between mt-8">
                <div class="block">
                    <label for="remember_me" class="flex">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('Recordarme') }}</span>
                    </label>
                </div>

                <x-button>
                    {{ __('Iniciar sesión') }}
                </x-button>

                {{-- @if (Route::has('register'))
                    <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                        {{ __('Registrarse') }}
                    </a>
                @endif --}}
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
