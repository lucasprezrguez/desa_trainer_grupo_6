@extends('layouts.adminlte')

@section('title', 'Usuarios')

@section('content_header')
    @component('components.content_header', [
        'title' => 'Usuarios',
        'buttonText' => 'Añadir Nuevo',
        'buttonTarget' => '#createUserModal',
        'description' =>
            'Administra todos los usuarios registrados desde esta tabla. Puedes añadir nuevos usuarios, editar la información existente o eliminar usuarios que ya no necesites. Utiliza el botón de acción Editar en cada fila para realizar estas tareas o bien haz clic en la fila y editarlo.',
    ])
    @endcomponent
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>Nombre</th>
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
                            <img src="{{ asset('images/pfp.png') }}" alt="" width="32" height="32" class="mr-2 rounded-circle">{{ $user->name }}
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

    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createUserModalLabel">Añadir Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    <div class="modal-body">
                        @include('admin.users.create')
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Usuario -->
    @foreach ($users as $user)
        <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog"
            aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Editar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-form-{{ $user->id }}" action="{{ route('users.update', $user->id) }}"
                        method="POST">
                        <div class="modal-body">
                            @include('admin.users.edit', ['user' => $user])
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary"
                                    onclick="document.getElementById('edit-form-{{ $user->id }}').submit();">Guardar</button>
                                <button href="#" class="btn btn-link"
                                    onclick="generatePassword({{ $user->id }}); return false;"><u>Resetear
                                        contraseña</u></button>
                            </div>
                            <button type="button" class="btn btn-light text-danger"
                                onclick="deleteUser({{ $user->id }});">Eliminar</button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}"
                        method="POST" style="display:none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('js')
    <script>
        function deleteUser(userId) {
            const SwalBS = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-light btn-lg text-danger',
                    cancelButton: 'btn btn-light btn-lg'
                },
                buttonsStyling: false
            });

            SwalBS.fire({
                title: '¿Eliminar Usuario?',
                text: "Esta acción es irreversible.",
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + userId).submit();
                }
            });
        }

        function generatePassword(userId) {
            const resetLink = document.querySelector(`button[onclick="generatePassword(${userId}); return false;"]`);
            resetLink.style.pointerEvents = 'none';
            resetLink.style.opacity = '0.5';
            document.body.style.cursor = 'wait';

            $.ajax({
                url: '/panel/users/' + userId + '/generate-password',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            text: response.message,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            text: response.message,
                            timer: 3000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    }
                },
                complete: function() {
                    resetLink.style.pointerEvents = 'auto';
                    resetLink.style.opacity = '1';
                    document.body.style.cursor = 'auto';
                }
            });
        }

        $(document).ready(function() {
            @if (session('success'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    text: '{{ session('success') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    text: '{{ session('error') }}',
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false
                });
            @endif

            $('tbody tr').on('click', function(e) {
                if (!$(e.target).is('a')) {
                    $(this).find('a[data-toggle="modal"]').click();
                }
            });

            $('.results').DataTable({
                "language": {
                    "lengthMenu": "_MENU_",
                    "zeroRecords": "No hay resultados.",
                    "search": "Buscar:",
                },
                "layout": {
                    "topStart": 'search',
                    "topEnd": 'paging',
                    "bottomStart": null,
                    "bottomEnd": null,
                },
                "ordering": false,
                "paging": true,
                "autoWidth": true,
                "responsive": true,
            });
        });
    </script>
    <script src="{{ asset('js/bundle.js') }}"></script>
@stop