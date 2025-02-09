@extends('layouts.adminlte')

@section('title', 'Usuarios')

@section('content_header')
    <x-content-header />
@stop

@section('content')
    <table class="table table-borderless table-hover results">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Perfil</th>
                <th>Correo Electrónico</th>
                <th></th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                @if (auth()->user()->id !== $user->id)
                    <tr data-toggle="modal" data-target="#editModal-{{ $user->id }}" style="cursor: pointer;">
                        <td>{{ $user->name }}</td>
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

@section('footer')
    <x-footer />
@stop

@section('js')
    <script>
        window.session = {
            success: @json(session('success')),
            error: @json(session('error'))
        };

        function generatePassword(userId) {
            const resetLink = document.querySelector(`button[onclick="generatePassword(${userId}); return false;"]`);
            resetLink.style.pointerEvents = 'none';
            resetLink.style.opacity = '0.5';
            document.body.style.cursor = 'wait';

            $.ajax({
                url: '/panel/users/' + userId + '/generate-password',
                method: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: response.success ? 'success' : 'error',
                        text: response.message,
                        timer: 3000,
                        timerProgressBar: true,
                        showConfirmButton: false
                    });
                },
                complete: function() {
                    resetLink.style.pointerEvents = 'auto';
                    resetLink.style.opacity = '1';
                    document.body.style.cursor = 'auto';
                }
            });
        }
    </script>
    <script src="{{ asset('js/bundle.js') }}"></script>
@stop
