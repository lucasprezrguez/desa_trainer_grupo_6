@csrf
@method('PUT')
<div class="form-group">
    <label for="instruction_name">Instrucción</label>
    <input type="text" name="instruction_name" class="form-control" value="{{ $instruction->instruction_name }}" required>
</div>
<div class="form-group">
    <label for="require_action">Requiere Acción</label>
    <select name="require_action" class="form-control custom-select" required>
        <option value="1" {{ $instruction->require_action ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ !$instruction->require_action ? 'selected' : '' }}>No</option>
    </select>
</div>
<div class="form-group">
    <label for="waiting_time">Espera (s)</label>
    <input type="number" name="waiting_time" class="form-control" value="{{ $instruction->waiting_time }}" required>
</div>
<p class="text-muted ml-2 mb-0">Por favor, asegúrate de que toda la información es correcta antes de guardar la instrucción.</p>
