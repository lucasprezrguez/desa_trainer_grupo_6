@extends('layouts.adminlte')

@section('title', 'Instrucciones')

@section('content_header')
    @component('components.content_header', [
        'title' => 'Instrucciones',
        'buttonText' => 'Añadir Nuevo',
        'buttonTarget' => '#createUserModal',
        'description' =>
            'Administra todas las instrucciones registradas desde esta tabla. Puedes añadir nuevas instrucciones, editar la información existente o eliminar instrucciones que ya no necesites. Utiliza el botón de acción Editar en cada fila para realizar estas tareas o bien haz clic en la fila y editarlo.',
    ])
    @endcomponent
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>#</th>
                <th>Contenido</th>
                <th>Requiere Acción</th>
                <th>Tipo de Acción</th>
                <th>Tiempo de Espera</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="6">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($instructions as $instruction)
                <tr>
                    <td>{{ $instruction->id }}</td>
                    <td>{{ $instruction->text_content }}</td>
                    <td>{{ $instruction->require_action ? 'Sí' : 'No' }}</td>
                    <td>{{ $instruction->action_type }}</td>
                    <td>{{ $instruction->waiting_time }}</td>
                    <td class="text-right">
                        <a href="#" class="" data-toggle="modal" data-target="#editInstructionModal-{{ $instruction->id }}">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Modal Crear Instrucción -->
    <div class="modal fade" id="createInstructionModal" tabindex="-1" role="dialog" aria-labelledby="createInstructionModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createInstructionModalLabel">Añadir Instrucción</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('instructions.store') }}" method="POST">
                    <div class="modal-body">
                        @include('admin.instructions.create')
                    </div>
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Instrucción -->
    @foreach ($instructions as $instruction)
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
                        <div class="modal-body">
                            @include('admin.instructions.edit', ['instruction' => $instruction])
                        </div>
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
