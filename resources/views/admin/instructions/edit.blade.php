@csrf
@method('PUT')
<div class="form-group">
    <label for="instruction_name">Nombre de Instrucción</label>
    <input type="text" name="instruction_name" class="form-control" value="{{ $instruction->instruction_name }}" required>
</div>
<div class="form-group">
    <label for="tts_description">Descripción TTS</label>
    <textarea name="tts_description" class="form-control" required>{{ $instruction->tts_description }}</textarea>
</div>
<div class="form-group">
    <label for="require_action">Requiere Acción</label>
    <select name="require_action" class="form-control" required>
        <option value="1" {{ $instruction->require_action ? 'selected' : '' }}>Sí</option>
        <option value="0" {{ !$instruction->require_action ? 'selected' : '' }}>No</option>
    </select>
</div>
<div class="form-group">
    <label for="type">Tipo</label>
    <select name="type" class="form-control" required>
        <option value="discharge" {{ $instruction->type == 'discharge' ? 'selected' : '' }}>Descarga</option>
        <option value="electrodes" {{ $instruction->type == 'electrodes' ? 'selected' : '' }}>Electrodos</option>
        <option value="none" {{ $instruction->type == 'none' ? 'selected' : '' }}>Ninguna</option>
    </select>
</div>
<div class="form-group">
    <label for="waiting_time">Tiempo de Espera (segundos)</label>
    <input type="number" name="waiting_time" class="form-control" value="{{ $instruction->waiting_time }}" required>
</div>
<p class="text-muted ml-2 mb-0">Por favor, asegúrate de que toda la información es correcta antes de guardar la instrucción.</p>
