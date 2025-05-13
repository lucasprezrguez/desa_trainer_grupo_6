@extends('layouts.adminlte')

@section('title', 'Panel de Administración')

@section('content_header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
        <div>
            <h3>BPM del Metrónomo</h3>
            <div class="form-group">
                <div class="btn-group w-100" role="group">
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

        <div class="mt-4">
            <h3>Habilitar/Deshabilitar Escenarios</h3>
            <div class="d-flex flex-wrap gap-2">
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

<style>
    .btn-group .btn {
        flex: 1 1 auto;
    }
    .btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
    .scenario-btn {
        width: 50px;
        height: 50px;
        margin: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        font-weight: bold;
    }
</style>