@extends('layouts.adminlte')

@section('title', 'Gestión de DESA')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between" style="gap: 10px;">
        <div class="d-flex align-items-center mt-4 mb-2" style="gap: 10px;">
            <h1>Lista de Dispositivos</h1>
            <button class="btn btn-outline-primary btn-sm" data-toggle="modal" data-target="#createDeviceModal">Añadir Nuevo </button>

            <!-- Modal Crear Dispositivo -->
<div class="modal fade" id="createDeviceModal" tabindex="-1" role="dialog" aria-labelledby="createDeviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createDeviceModalLabel">Añadir Nuevo Dispositivo</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('devices.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Nombre del Dispositivo</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="form-group">
                        <label for="on_led">LED Encendido</label>
                        <select class="form-control" id="on_led" name="on_led">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="pause_state">Estado de Pausa</label>
                        <select class="form-control" id="pause_state" name="pause_state">
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="display_message">Mensaje de Display</label>
                        <textarea class="form-control" id="display_message" name="display_message"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Imagen de Fondo</label>
                        <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Crear Dispositivo</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </form>
        </div>
    </div>
</div>



        </div>
    </div>
    <p class="text-muted w-75">En esta tabla puedes ver todos los dispositivos registrados. Puedes añadir nuevos dispositivos, editar la información existente o eliminar dispositivos que ya no necesites. Usa los botones de acción en cada fila para realizar estas tareas.</p>
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
            <th>Imagen</th>
            <th class="text-right">Acciones</th>
        </tr>
        <tr class="warning no-result" style="display:none;">
            <td colspan="7">No hay resultados.</td>
        </tr>
    </thead>
    <tbody>
        @foreach ($devices as $device)
            <tr>
                <td>{{ $device->id }}</td>
                <td>{{ $device->name }}</td>
                <td>{{ $device->is_online ? 'Online' : 'Offline' }}</td>
                <td>{{ $device->progress ?? 0 }}%</td>
                <td>{{ $device->current_scenario ?? 'No asignado' }}</td>
                <td>
                    @if ($device->image)
                        <img src="{{ asset('storage/device_images/' . $device->image) }}" alt="Imagen del dispositivo" style="width: 100px; height: auto;">
                    @else
                        <span class="text-muted">Sin Imagen</span>
                    @endif
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
