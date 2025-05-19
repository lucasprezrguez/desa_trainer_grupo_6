@extends('layouts.adminlte')

@section('title', 'Panel de Administración')

@section('content_header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
        <div class="d-flex">
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

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection

@section('js')
    <script>
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
    </script>
@endsection