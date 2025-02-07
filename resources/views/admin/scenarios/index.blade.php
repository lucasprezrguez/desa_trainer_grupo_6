@extends('layouts.adminlte')

@section('title', 'Escenarios')

@section('content_header')
    @component('components.content_header', [
        'title' => 'Escenarios',
        'buttonText' => 'Añadir Nuevo',
        'buttonTarget' => '#createScenarioModal',
        'description' =>
            'Administra todos los escenarios registrados desde esta tabla. Puedes añadir nuevos escenarios, editar la información existente o eliminar escenarios que ya no necesites. Utiliza el botón de acción Editar en cada fila para realizar estas tareas o bien haz clic en la fila y editarlo.',
    ])
    @endcomponent
@stop

@section('content')
    <table class="table table-striped shadow-sm bg-white rounded results">
        <thead>
            <tr>
                <th>Escenario</th>
                <th>Códigos de los escenarios</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($scenarios as $scenario)
                <tr>
                    <td>{{ $scenario->scenario_name }}</td>
                    <td><img src="{{ asset($scenario->image_url) }}" alt="Código del escenario" style="width: auto; height: 36px;"></td>
                    <td class="text-right">
                        <a href="#" class="" data-toggle="modal" data-target="#editScenarioModal-{{ $scenario->id }}">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <!-- Modal Crear Escenario -->
    <div class="modal fade" id="createScenarioModal" tabindex="-1" role="dialog" aria-labelledby="createScenarioModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createScenarioModalLabel">Añadir Escenario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('scenarios.store') }}" method="POST">
                    <!-- <div class="modal-body">
                        @include('admin.scenarios.create')
                    </div> -->
                    <div class="modal-footer d-flex justify-content-start">
                        <button type="submit" class="btn btn-primary">Añadir</button>
                        <button type="button" class="btn btn-link" data-dismiss="modal">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Editar Escenario -->
    @foreach ($scenarios as $scenario)
        <div class="modal fade" id="editScenarioModal-{{ $scenario->id }}" tabindex="-1" role="dialog" aria-labelledby="editScenarioModalLabel-{{ $scenario->id }}" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editScenarioModalLabel-{{ $scenario->id }}">Editar Escenario</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form id="edit-form-{{ $scenario->id }}" action="{{ route('scenarios.update', $scenario->id) }}" method="POST">
                        <!-- <div class="modal-body">
                            @include('admin.scenarios.edit', ['scenario' => $scenario])
                        </div> -->
                        <div class="modal-footer d-flex justify-content-between">
                            <div>
                                <button type="button" class="btn btn-primary" onclick="document.getElementById('edit-form-{{ $scenario->id }}').submit();">Guardar</button>
                            </div>
                            <button type="button" class="btn btn-light text-danger" onclick="deletescenario({{ $scenario->id }});">Eliminar</button>
                        </div>
                    </form>
                    <form id="delete-form-{{ $scenario->id }}" action="{{ route('scenarios.destroy', $scenario->id) }}" method="POST" style="display:none;">
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
        function deleteScenario(scenarioId) {
            const SwalBS = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-light btn-lg text-danger',
                    cancelButton: 'btn btn-light btn-lg'
                },
                buttonsStyling: false
            });

            SwalBS.fire({
                title: '¿Eliminar Escenario?',
                text: "Esta acción es irreversible.",
                showCancelButton: true,
                confirmButtonText: 'Eliminar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + scenarioId).submit();
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
@stop
