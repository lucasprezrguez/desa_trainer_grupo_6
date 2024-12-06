@extends('layouts.adminlte')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Lista de Usuarios</h1>
        <button class="btn btn-success" data-toggle="modal" data-target="#createUserModal">Crear Usuario</button>
    </div>
@stop

@section('content')
    @if(session('success'))
        <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; bottom: 60px; left: 50%; transform: translateX(-50%);" data-delay="5000">
            <div class="d-flex">
                <div class="toast-body">
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <table class="table table-striped shadow-sm bg-white rounded">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Correo Electrónico</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <a href="#" data-toggle="modal" data-target="#editUserModal-{{ $user->id }}">Editar</a>
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
                    <h5 class="modal-title" id="createUserModalLabel">Crear Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('users.store') }}" method="POST">
                    <div class="modal-body">
                        @include('admin.users.create')
                    </div>
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-success">Guardar</button>
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
                            <button type="button" class="btn btn-success" onclick="document.getElementById('edit-form-{{ $user->id }}').submit();">Guardar</button>
                            <button type="button" class="btn btn-danger" onclick="deleteUser({{ $user->id }});">Eliminar</button>
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
    if (confirm('¿Estás seguro?')) {
        document.getElementById('delete-form-' + userId).submit();
    }
}

$(document).ready(function() {
    @if(session('success'))
        $('.toast').toast('show');
    @endif
});
</script>
@stop