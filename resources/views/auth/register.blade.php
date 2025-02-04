<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <h2 class="text-3xl font-semibold mb-6">{{ __('Registro') }}</h2>

        <form method="POST" action="{{ route('register') }}" class="m-0">
            @csrf

            <div class="relative w-full">
                <x-input id="name" type="text" name="name" :value="old('name')" required autofocus autocomplete="name">
                    <x-label for="name">Nombre</x-label>
                </x-input>
            </div>

            <div class="relative w-full mt-4">
                <x-input id="email" type="email" name="email" :value="old('email')" required autocomplete="username">
                    <x-label for="email">Correo Electrónico</x-label>
                </x-input>
            </div>

            <div class="relative w-full mt-4 flex">
                <div class="w-1/2 pr-2">
                    <x-input id="password" type="password" name="password" required autocomplete="new-password">
                        <x-label for="password">Contraseña</x-label>
                    </x-input>
                </div>

                <div class="w-1/2 pl-2">
                    <x-input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password">
                        <x-label for="password_confirmation">Confirmar</x-label>
                    </x-input>
                </div>
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms" required />

                            <div class="ms-2">
                                {!! __('Estoy de acuerdo con los :terms_of_service y :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Términos de Servicio').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">'.__('Política de Privacidad').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                    {{ __('Iniciar sesión') }}
                </a>

                <x-button class="ms-4">
                    {{ __('Registrarse') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
