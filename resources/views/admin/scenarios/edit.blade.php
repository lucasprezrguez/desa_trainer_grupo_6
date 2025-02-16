@csrf
@method('PUT')
<div class="form-group">
    <label for="scenario_name">Nombre del Escenario</label>
    <input type="text" class="form-control" id="scenario_name" name="scenario_name" 
           value="{{ old('scenario_name', $scenario->scenario_name) }}" required>
</div>

<div class="mt-4">
    <h5>Instrucciones asociadas:</h5>
    @forelse($scenario->instructions as $instruction)
        <div class="card mb-3">
            <div class="card-body">
                <h6 class="card-title">
                    #{{ $instruction->pivot->order }} - {{ $instruction->instruction_name }}
                    <small class="text-muted">(Repeticiones: {{ $instruction->pivot->reps }})</small>
                </h6>
                
                @php
                    // Convertir parámetros a array si es necesario
                    $params = is_array($instruction->pivot->params) 
                        ? $instruction->pivot->params 
                        : json_decode($instruction->pivot->params, true);
                @endphp

                @if(!empty($params) && is_array($params))
                    <div class="ml-3">
                        <strong>Parámetros:</strong>
                        <ul>
                            @foreach($params as $key => $value)
                                <li>
                                    <code>{{ $key }}:</code>
                                    @if(is_array($value))
                                        {{ implode(', ', $value) }}
                                    @else
                                        {{ $value }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @else
                    <div class="ml-3 text-muted">Sin parámetros configurados</div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-info">No hay instrucciones asociadas</div>
    @endforelse
</div>