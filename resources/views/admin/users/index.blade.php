@extends('layouts.adminlte')

@section('title', 'Gestión de Usuarios')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
        <div class="d-flex align-items-center" style="gap: 10px;">
            <h1>Lista de Usuarios</h1>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createUserModal">Añadir Nuevo</button>
        </div>
        <div class="search-bar">
            <input type="text" class="search form-control" placeholder="Buscar...">
        </div>
    </div>
@stop

@section('content')
    <table class="table shadow-sm bg-white rounded results">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 40%;">Nombre</th>
                <th style="width: 45%;">Correo Electrónico</th>
                <th style="width: 10%;">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
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
                        <button type="submit" class="btn btn-primary">Guardar</button>
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
                            <button type="button" class="btn btn-primary" onclick="document.getElementById('edit-form-{{ $user->id }}').submit();">Guardar</button>
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
});

$(".search").keyup(function () {
    var searchTerm = $(".search").val().toLowerCase();
    var listItem = $('.results tbody').children('tr');
    
    $.extend($.expr[':'], {'containsi': function(elem, i, match, array){
        return (elem.textContent || elem.innerText || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
    }});

    var jobCount = 0;
    $(".results tbody tr").each(function() {
        var rowText = $(this).text().toLowerCase();
        if (rowText.indexOf(searchTerm) === -1) {
            $(this).hide();
        } else {
            $(this).show();
            jobCount++;
        }
    });

    if (jobCount == '0') {
        $('.no-result').show();
    } else {
        $('.no-result').hide();
    }
});
</script>
@stop