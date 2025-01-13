@csrf
@method('PUT')
<div class="form-group">
    <label for="text_content">Contenido</label>
    <textarea name="text_content" class="form-control" required>{{ $instruction->text_content }}</textarea>
</div>
<div class="form-group">
    <label for="require_action">Requiere Acción</label>
    <select name="require_action" class="form-control" required>
        <option value="1" {{ $instruction->require_action ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ !$instruction->require_action ? 'selected' : '' }}>No</option>
    </select>
</div>
<div class="form-group">
    <label for="action_type">Tipo de Acción</label>
    <select name="action_type" class="form-control" required>
        <option value="discharge" {{ $instruction->action_type == 'discharge' ? 'selected' : '' }}>Descarga</option>
        <option value="electrodes" {{ $instruction->action_type == 'electrodes' ? 'selected' : '' }}>Electrodos</option>
        <option value="none" {{ $instruction->action_type == 'none' ? 'selected' : '' }}>Ninguna</option>
    </select>
</div>
<div class="form-group">
    <label for="waiting_time">Tiempo de Espera (segundos)</label>
    <input type="number" name="waiting_time" class="form-control" value="{{ $instruction->waiting_time }}" required>
</div>
<p class="text-muted ml-2 mb-0">Por favor, asegúrate de que toda la información es correcta antes de guardar la instrucción.</p>
