@csrf
@method('PUT')
<div class="form-group">
    <label for="scenario_name">Escenario</label>
    <input type="text" class="form-control" id="scenario_name" name="scenario_name" 
           value="{{ old('scenario_name', $scenario->scenario_name) }}" required>
</div>

<div class="mt-4">
    <p class="font-weight-bold">Instrucciones:</p>
    @forelse($scenario->instructions as $instruction)
        <div class="card mb-3">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <span class="badge badge-light mr-2">{{ $instruction->pivot->order }}</span>
                        <span>{{ $instruction->instruction_name }}</span>
                    </div>
                    <span class="text-muted"><i class="ri-loop-left-line mr-1"></i>{{ $instruction->pivot->reps }}</span>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info">No hay instrucciones asociadas</div>
    @endforelse
</div>