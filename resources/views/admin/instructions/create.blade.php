@csrf
<div class="form-group">
    <label for="text_content">Contenido</label>
    <textarea name="text_content" class="form-control" required></textarea>
</div>
<div class="form-group">
    <label for="require_action">Requiere Acción</label>
    <select name="require_action" class="form-control" required>
        <option value="1">Sí</option>
        <option value="0">No</option>
    </select>
</div>
<div class="form-group">
    <label for="action_type">Tipo de Acción</label>
    <select name="action_type" class="form-control" required>
        <option value="discharge">Descarga</option>
        <option value="electrodes">Electrodos</option>
        <option value="none">Ninguna</option>
    </select>
</div>
<div class="form-group">
    <label for="waiting_time">Tiempo de Espera (segundos)</label>
    <input type="number" name="waiting_time" class="form-control" required>
</div>
<p class="text-muted ml-2 mb-0">Por favor, asegúrate de que toda la información es correcta antes de añadir la instrucción.</p>
