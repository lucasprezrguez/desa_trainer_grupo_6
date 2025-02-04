@extends('layouts.adminlte')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
        <div class="d-flex align-items-center mt-4 mb-2" style="gap: 10px;">
            <h1>Lista de Usuarios</h1>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createUserModal">Añadir Nuevo</button>
        </div>
    </div>
    <p class="text-muted w-75">En esta tabla puedes ver todos los usuarios registrados. Puedes añadir nuevos usuarios, editar la información existente o eliminar usuarios que ya no necesites. Usa los botones de acción en cada fila para realizar estas tareas.</p>
@stop

@section('content')
    <style>
        .user-role-badge {
            display: none;
        }
        tr:hover .user-role-badge {
            display: inline;
        }
    </style>
    <table class="table table-striped shadow-sm bg-white rounded results">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr 
                    @if (auth()->user()->id !== $user->id)
                        data-toggle="modal" data-target="#editUserModal-{{ $user->id }}" style="cursor: pointer;"
                    @else
                        style="cursor: not-allowed;"
                    @endif
                >
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }} <span class="badge user-role-badge">{{ ucfirst($user->roles) }}</span></td>
                    <td>{{ $user->email }}</td>
                    <td class="text-right">
                        @if (auth()->user()->id !== $user->id)
                            <a href="#" class="" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">Editar</a>
                        @else
                            <span class="text-muted">Tú</span>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Modal Crear Usuario -->
    <div class="modal fade" id="createUserModal" tabindex="-1" role="dialog" aria-labelledby="createUserModalLabel" aria-hidden="true">
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
        <div class="modal fade" id="editUserModal-{{ $user->id }}" tabindex="-1" role="dialog" aria-labelledby="editUserModalLabel-{{ $user->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editUserModalLabel-{{ $user->id }}">Editar Usuario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-form-{{ $user->id }}" action="{{ route('users.update', $user->id) }}" method="POST">
                        <div class="modal-body">
                            @include('admin.users.edit', ['user' => $user])
                        </div>
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('edit-form-{{ $user->id }}').submit();">Guardar</button>
                                <button href="#" class="btn btn-link" onclick="generatePassword({{ $user->id }}); return false;"><u>Resetear contraseña</u></button>
                            </div>
                            <button type="button" class="btn btn-light text-danger" onclick="deleteUser({{ $user->id }});">Eliminar</button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:none;">
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
    @if(session('success'))
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

    @if(session('error'))
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
});

$(document).ready(function() {
    $('.results').DataTable({
        "language": {
            "lengthMenu": "_MENU_",
            "zeroRecords": "No hay resultados.",
            "search": "Buscar:",
        },
        "ordering": false,
        "select": true,
        "paging": true,
        "autoWidth": true,
        "responsive": true,
        "layout": {
            "topStart": 'search',
            "topEnd": 'pageLength',
            "bottomStart": null,
            "bottomEnd": 'paging',
        }
    });
});
</script>
@stop
