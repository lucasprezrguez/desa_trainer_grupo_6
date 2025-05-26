@extends('layouts.adminlte')

@section('title', 'Panel de Administración')

@section('content_header')
    <h1>Panel de Administración</h1>
    <p class="w-75 text-muted mt-2">
    Desde este panel puedes visualizar estadísticas de uso, ajustar configuraciones clave del sistema y gestionar la disponibilidad de escenarios y parámetros de entrenamiento. Utiliza las opciones de abajo para personalizar la experiencia de los usuarios y monitorear el desempeño general.
    </p>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">
                            Alumnos con más escenarios completados
                        </h3>
                        <i class="ri-question-line ml-2" data-toggle="tooltip" data-placement="top" title="Pasa el cursor sobre las barras para ver el detalle de escenarios completados."></i>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="topUsersChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title mb-0">Escenarios más completados</h3>
                        <i class="ri-question-line ml-2" data-toggle="tooltip" data-placement="top" title="Pasa el cursor sobre el gráfico para ver el porcentaje de finalización de cada escenario."></i>
                    </div>
                </div>
                <div class="card-body">
                    <canvas id="scenarioStatsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0">Ajustes</h3>
                <i class="ri-question-line ml-2" data-toggle="tooltip" data-placement="top" title="Configura la duración de la RCP, el BPM del metrónomo y la disponibilidad de escenarios."></i>
            </div>
        </div>
        <div class="card-body">
            <div class="d-flex">
                <div>
                    <h5 class="font-weight-bold">Duración de la RCP</h5>
                    <p class="w-75 text-muted mt-2">Ajusta la duración del protocolo de Reanimación cardiopulmonar. El valor por defecto es 2 minutos.</p>
                </div>
                <div class="form-group align-self-center" style="width: 34%;">
                    <label for="rcpDurationRange" class="mb-1 d-flex align-items-center" style="gap: 4px;">
                        Duración: <span id="rcpDurationLabel">{{ number_format(($instructions->firstWhere('instruction_id', 4)->waiting_time ?? 120) / 60, 1, ',', '') }}</span> min
                        <span id="rcpResetBtn" style="cursor:pointer;display:{{ number_format(($instructions->firstWhere('instruction_id', 4)->waiting_time ?? 120) / 60, 1, '.', '') != '2.0' ? 'inline' : 'none' }}; color:#007bff;">
                            <i class="ri-restart-line"></i>
                        </span>
                    </label>
                    <input type="range" class="custom-range" min="1" max="3" step="0.5" id="rcpDurationRange" value="{{ number_format(($instructions->firstWhere('instruction_id', 4)->waiting_time ?? 120) / 60, 1, '.', '') }}">
                </div>
            </div>

            <div class="d-flex mt-4">
                <div>
                    <h5 class="font-weight-bold">BPM del Metrónomo</h5>
                    <p class="w-75 text-muted mt-2">Selecciona la velocidad del metrónomo en compresiones por minuto (BPM) para marcar el ritmo adecuado durante las compresiones torácicas.</p>
                </div>
                <div class="form-group">
                    <div class="btn-group btn-group-lg" role="group">
                        @foreach([100, 110, 120] as $bpm)
                            <button 
                                type="button"
                                class="btn btn-outline-primary {{ session('metronome_bpm', 110) == $bpm ? 'active' : '' }}"
                                data-bpm="{{ $bpm }}"
                                onclick="updateBpm({{ $bpm }})"
                            >
                                {{ $bpm }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="d-flex mt-4">
                <div>
                    <h5 class="font-weight-bold">Habilitar/Deshabilitar Escenarios</h5>
                    <p class="w-75 text-muted mt-2">Activa o desactiva escenarios para incluirlos o excluirlos de las actividades. Los habilitados estarán disponibles en la cajonera del trainer.</p>
                </div>
                <div class="d-flex flex-wrap justify-content-end" style="width: 34%; gap: .5rem;">
                    @foreach(range(1, 8) as $scenarioId)
                        <button 
                            type="button" 
                            class="btn scenario-btn {{ $scenarios->contains('scenario_id', $scenarioId) && $scenarios->firstWhere('scenario_id', $scenarioId)->is_enabled ? 'btn-primary' : 'btn-outline-primary' }}"
                            onclick="toggleScenario({{ $scenarioId }})"
                        >
                            {{ $scenarioId }}
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <x-footer />

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection

@section('js')
    <script>
        window.updateBpmUrl = "{{ route('update.bpm') }}";
        window.toggleScenarioUrl = "{{ route('toggle.scenarios') }}";
        window.updateWaitingTimeUrl = "{{ route('update.waiting_time') }}";
        window.csrfToken = "{{ csrf_token() }}";
        window.topUsersLabels = {!! json_encode($topUsers->pluck('name')) !!};
        window.topUsersData = {!! json_encode($topUsers->pluck('completed')) !!};
        window.topUsersScenarios = {!! json_encode($topUsers->pluck('scenarios')) !!};
        window.scenarioStatsLabels = {!! json_encode($scenarioStats->pluck('name')) !!};
        window.scenarioStatsData = {!! json_encode($scenarioStats->pluck('completed')) !!};
    </script>
    <script src="/js/bundle.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection