@extends('layouts.adminlte')

@section('title', 'Usuarios')

@section('content_header')
    <x-content-header />
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Perfil</th>
                <th>Correo Electrónico</th>
                <th class="text-right"></th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if (auth()->user()->id !== $user->id)
                    <tr data-toggle="modal" data-target="#editModal-{{ $user->id }}" style="cursor: pointer;">
                        <td>
                            @php
                                $inicial = strtoupper(substr($user->name, 0, 1));
                                $colorFondo = \App\Helpers\ColorHelper::generarColorWCAG($user->name);
                            @endphp
                            <img src="https://ui-avatars.com/api/?name={{ $inicial }}&background={{ $colorFondo }}&color=ffffff&size=32" 
                                 alt="{{ $user->name }}" 
                                 width="32" 
                                 height="32"
                                 class="mr-2 rounded-circle">
                            {{ $user->name }}
                        </td>
                        <td>
                            @if ($user->roles == 'admin')
                                <span class="badge badge-primary"><i class="ri-shield-fill mr-1"></i>Administrador</span>
                            @elseif ($user->roles == 'profesor')
                                <span class="badge badge-success"><i class="ri-edit-2-fill mr-1"></i>Profesor</span>
                            @elseif ($user->roles == 'alumno')
                                <span class="badge badge-light"><i class="ri-eye-fill mr-1"></i>Alumno</span>
                            @endif
                        </td>
                        <td>{{ $user->email }}</td>
                        <td class="text-right">
                            <a href="#" class="btn btn-light btn-sm" data-toggle="modal"
                                data-target="#editModal-{{ $user->id }}"><i class="ri-more-fill"></i></a>
                        </td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
    <x-footer />
</div>
    <x-modal id="createModal" title="Añadir Usuario" formId="create-form" action="{{ route('users.store') }}"
        submitText="Añadir" deleteText="Cancelar">
        @include('admin.users.create')
    </x-modal>

    @foreach ($users as $user)
        <x-modal id="editModal-{{ $user->id }}" title="Editar Usuario" formId="edit-form-{{ $user->id }}"
            action="{{ route('users.update', $user->id) }}" submitText="Guardar" deleteText="Eliminar"
            deleteId="{{ $user->id }}" deleteName="{{ $user->name }}"
            deleteAction="{{ route('users.destroy', $user->id) }}" resetPassword="{{ $user->id }}">
            @include('admin.users.edit', ['user' => $user])
        </x-modal>
    @endforeach

    
@stop

@section('js')
    <script>
        window.session = {
            success: @json(session('success')),
            error: @json(session('error'))
        };

        function generatePassword(userId) {
            const resetButton = document.querySelector(`button[data-user-id="${userId}"]`);

            if (!resetButton) {
                console.error('Button not found');
                return;
            }

            resetButton.classList.add('processing');
            document.documentElement.classList.add('wait-cursor');

            const toastSettings = {
                toast: true,
                position: 'top-end',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            };

            const endpoint = `/panel/users/${encodeURIComponent(userId)}/generate-password`;

            (async () => {
                try {
                    const response = await fetch(endpoint, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        credentials: 'same-origin'
                    });

                    const data = await response.json();

                    if (!response.ok) throw new Error(data.message || 'Request failed');

                    Swal.fire({
                        ...toastSettings,
                        icon: 'success',
                        text: data.message
                    });
                } catch (error) {
                    Swal.fire({
                        ...toastSettings,
                        icon: 'error',
                        text: error.message || 'Error generating password'
                    });
                } finally {
                    resetButton.classList.remove('processing');
                    document.documentElement.classList.remove('wait-cursor');
                }
            })();
        }
    </script>
    <script src="{{ asset('js/bundle.js') }}"></script>
@stop
