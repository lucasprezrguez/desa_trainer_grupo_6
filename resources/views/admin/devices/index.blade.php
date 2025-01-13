@extends('layouts.adminlte')

@section('title', 'Gestión de DESA')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
        <div class="d-flex align-items-center mt-4 mb-2" style="gap: 10px;">
            <h1>Lista de DESA</h1>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createDESAModal">Añadir Nuevo</button>
        </div>
    
    </div>
    <div class="d-flex w-100">
        <p class="text-muted">
            {{$nombre}}
            En esta tabla puedes gestionar los dispositivos DESA. Puedes añadir nuevos dispositivos, editar su información o eliminarlos según sea necesario. Usa los botones de acción en cada fila para realizar estas tareas.
        </p>
        <div>
        
        </div>    
    </div>
@stop

@section('content')
    <table class="table table-striped shadow-sm bg-white rounded results">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Progreso (%)</th>
                <th>Escenario Actual</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="6">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($devices as $device)
                <tr>
                    <td>{{ $device->id }}</td>
                    <td>{{ $device->name }}</td>
                    <td>
                        {{-- Simulación del estado (sustituir por lógica real) --}}
                        {{ $device->is_online ? 'Online' : 'Offline' }}
                    </td>
                    <td>
                        {{-- Simulación del progreso (sustituir por lógica real) --}}
                        {{ $device->progress ?? 0 }}%
                    </td>
                    <td>
                        {{-- Simulación del escenario actual (sustituir por lógica real) --}}
                        {{ $device->current_scenario ?? 'No asignado' }}
                    </td>
                    <td class="text-right">
                        <a href="#" class="text-primary" data-toggle="modal" data-target="#editDESAModal-{{ $device->id }}">Editar</a>
                        <a href="#" class="text-danger" onclick="deleteDESA({{ $device->id }});">Eliminar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal Crear DESA -->
    <div class="modal fade" id="createDESAModal" tabindex="-1" role="dialog" aria-labelledby="createDESAModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createDESAModalLabel">Añadir DESA</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('devices.store') }}" method="POST">
                    @csrf
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar DESA -->
    @foreach ($devices as $device)
        <div class="modal fade" id="editDESAModal-{{ $device->id }}" tabindex="-1" role="dialog" aria-labelledby="editDESAModalLabel-{{ $device->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDESAModalLabel-{{ $device->id }}">Editar DESA</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="{{ route('devices.update', $device->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            @include('devices.edit', ['device' => $device])
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@stop

@section('js')
<script>
function deleteDESA(deviceId) {
    const SwalBS = Swal.mixin({
        customClass: {
            confirmButton: 'btn btn-light btn-lg text-danger',
            cancelButton: 'btn btn-light btn-lg'
        },
        buttonsStyling: false
    });

    SwalBS.fire({
        title: '¿Eliminar DESA?',
        text: "Esta acción es irreversible.",
        showCancelButton: true,
        confirmButtonText: 'Eliminar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('delete-form-' + deviceId).submit();
        }
    });
}
</script>
@stop
