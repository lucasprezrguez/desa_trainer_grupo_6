@extends('layouts.adminlte')

@section('title', 'Gestión de Escenarios')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
        <div class="d-flex align-items-center mt-4 mb-2" style="gap: 10px;">
            <h1>Lista de Escenarios</h1>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createInstructionModal">Añadir Nuevo</button>
        </div>
    </div>
    <p class="text-muted w-75">En esta tabla puedes ver todas las instrucciones registradas. Puedes añadir nuevos escenarios, editar la información existente o eliminar instrucciones que ya no necesites. Usa los botones de acción en cada fila para realizar estas tareas.</p>
@stop

@section('content')
    <table class="table table-striped shadow-sm bg-white rounded results">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Número de descargas</th>
                <th>Intervalo Mínimo</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="6">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($scenarios as $scenario)
                <tr>
                    <td>{{ $scenario->id }}</td>
                    <td>{{ $scenario->text_content }}</td>
                    <td>{{ $scenario->require_action ? 'Sí' : 'No' }}</td>
                    <td>{{ $scenario->action_type }}</td>
                    <td>{{ $scenario->waiting_time }}</td>
                    <td class="text-right">
                        <a href="#" class="" data-toggle="modal" data-target="#editInstructionModal-{{ $instruction->id }}">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Modal Crear Escenario -->
    <div class="modal fade" id="createInstructionModal" tabindex="-1" role="dialog" aria-labelledby="createInstructionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInstructionModalLabel">Añadir Escenario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('instructions.store') }}" method="POST">
                    <!-- <div class="modal-body">
                        @include('admin.instructions.create')
                    </div> -->
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Instrucción -->
    @foreach ($scenarios as $scenario)
        <div class="modal fade" id="editInstructionModal-{{ $instruction->id }}" tabindex="-1" role="dialog" aria-labelledby="editInstructionModalLabel-{{ $instruction->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editInstructionModalLabel-{{ $instruction->id }}">Editar Instrucción</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-form-{{ $instruction->id }}" action="{{ route('instructions.update', $instruction->id) }}" method="POST">
                        <!-- <div class="modal-body">
                            @include('admin.instructions.edit', ['instruction' => $instruction])
                        </div> -->
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('edit-form-{{ $instruction->id }}').submit();">Guardar</button>
                            </div>
                            <button type="button" class="btn btn-light text-danger" onclick="deleteInstruction({{ $instruction->id }});">Eliminar</button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $instruction->id }}" action="{{ route('instructions.destroy', $instruction->id) }}" method="POST" style="display:none;">
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
function deleteInstruction(instructionId) {
    const SwalBS = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-light btn-lg text-danger',
            cancelButton: 'btn btn-light btn-lg'
        },
        buttonsStyling: false
    });

    SwalBS.fire({
        title: '¿Eliminar Instrucción?',
        text: "Esta acción es irreversible.",
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + instructionId).submit();
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
