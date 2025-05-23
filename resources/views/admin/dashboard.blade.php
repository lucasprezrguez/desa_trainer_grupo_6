@extends('layouts.adminlte')

@section('title', 'Panel de Administración')

@section('content_header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Alumnos con más escenarios completados</h3>
                </div>
                <div class="card-body">
                    <canvas id="topUsersChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Escenarios más completados</h3>
                </div>
                <div class="card-body">
                    <canvas id="scenarioStatsChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Ajustes</h3>
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
                    <p class="w-75 text-muted mt-2">Selecciona la velocidad del metrónomo en compresiones por minuto (BPM). Esto se usa en las instrucciones de RCP para marcar el ritmo adecuado durante las compresiones torácicas.</p>
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
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Top Users Chart
        const topUsersCtx = document.getElementById('topUsersChart').getContext('2d');
        new Chart(topUsersCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($topUsers->pluck('name')) !!},
                datasets: [{
                    label: 'Completados',
                    data: {!! json_encode($topUsers->pluck('completed')) !!},
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });

        // Scenario Stats Chart
        const scenarioStatsCtx = document.getElementById('scenarioStatsChart').getContext('2d');
        new Chart(scenarioStatsCtx, {
            type: 'pie',
            data: {
                labels: {!! json_encode($scenarioStats->pluck('name')) !!},
                datasets: [{
                    data: {!! json_encode($scenarioStats->pluck('completed')) !!},
                    backgroundColor: [
                        'rgba(60,141,188,0.9)',
                        'rgba(40,167,69,0.9)',
                        'rgba(255,193,7,0.9)',
                        'rgba(220,53,69,0.9)',
                        'rgba(23,162,184,0.9)'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    }
                }
            }
        });

        function updateBpm(bpm) {
            // Cambiar visualmente el botón activo
            document.querySelectorAll('.btn-group .btn').forEach(button => {
                button.classList.remove('active');
            });
            document.querySelector(`[data-bpm="${bpm}"]`).classList.add('active');

            // Enviar la actualización al servidor usando AJAX
            fetch('{{ route('update.bpm') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ bpm })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al actualizar el BPM');
                }
                return response.json();
            })
            .then(data => {
                console.log('BPM actualizado:', data);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Hubo un error al actualizar el BPM.');
            });
        }

        function toggleScenario(scenarioId) {
            const button = document.querySelector(`.scenario-btn:nth-child(${scenarioId})`);
            const isEnabled = button.classList.contains('btn-primary');

            // Cambiar visualmente el estado del botón
            button.classList.toggle('btn-primary', !isEnabled);
            button.classList.toggle('btn-outline-primary', isEnabled);

            // Enviar la actualización al servidor usando AJAX
            fetch('{{ route('toggle.scenarios') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    scenario_id: scenarioId,
                    is_enabled: !isEnabled
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error al guardar los cambios');
                }
                return response.json();
            })
            .then(data => {
                console.log('Estado actualizado:', data);
            })
            .catch(error => {
                console.error('Error:', error);
                // Revertir el cambio visual si hay un error
                button.classList.toggle('btn-primary', isEnabled);
                button.classList.toggle('btn-outline-primary', !isEnabled);
            });
        }

        // RCP Duration Range
        const rcpDurationRange = document.getElementById('rcpDurationRange');
        const rcpDurationLabel = document.getElementById('rcpDurationLabel');
        const rcpResetBtn = document.getElementById('rcpResetBtn');
        function updateResetBtnDisplay(val) {
            if (rcpResetBtn) {
                rcpResetBtn.style.display = (parseFloat(val) !== 2.0) ? 'inline' : 'none';
            }
        }
        if (rcpDurationRange && rcpDurationLabel) {
            updateResetBtnDisplay(rcpDurationRange.value);
            rcpDurationRange.addEventListener('input', function() {
                rcpDurationLabel.textContent = this.value.replace('.', ',');
                updateResetBtnDisplay(this.value);
            });
            rcpDurationRange.addEventListener('change', function() {
                const minutes = parseFloat(this.value);
                const seconds = Math.round(minutes * 60);
                fetch('{{ route('update.waiting_time') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ instruction_id: 4, waiting_time: seconds })
                })
                .then(response => {
                    if (!response.ok) throw new Error('Error al actualizar la duración de la RCP');
                    return response.json();
                })
                .then(data => {
                    console.log('Duración de RCP actualizada:', data);
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Hubo un error al actualizar la duración de la RCP.');
                });
            });
        }
        if (rcpResetBtn && rcpDurationRange && rcpDurationLabel) {
            rcpResetBtn.addEventListener('click', function() {
                rcpDurationRange.value = 2.0;
                rcpDurationLabel.textContent = '2,0';
                updateResetBtnDisplay(2.0);
                // Trigger change event to persist
                const event = new Event('change');
                rcpDurationRange.dispatchEvent(event);
            });
        }
    </script>
@endsection