@csrf
<div class="form-group">
    <label for="instruction_name">Nombre de Instrucción</label>
    <input type="text" name="instruction_name" class="form-control" required>
</div>
<div class="form-group">
    <label for="tts_description">Descripción TTS</label>
    <textarea name="tts_description" class="form-control" required></textarea>
</div>
<div class="form-group">
    <label for="require_action">Requiere Acción</label>
    <select name="require_action" class="form-control" required>
        <option value="1">Sí</option>
        <option value="0">No</option>
    </select>
</div>
<div class="form-group">
    <label for="type">Tipo</label>
    <select name="type" class="form-control" required>
        <option value="discharge">Descarga</option>
        <option value="electrodes">Electrodos</option>
        <option value="none">Ninguna</option>
    </select>
</div>
<div class="form-group">
    <label for="waiting_time">Tiempo de Espera (segundos)</label>
    <input type="number" name="waiting_time" class="form-control" required>
</div>
<div class="form-group">
    <label for="additional_info">Información Adicional</label>
    <textarea id="additional_info" name="additional_info" class="form-control wysiwyg-editor"></textarea>
    <small class="form-text text-muted">Esta información se mostrará en un modal al hacer clic en el icono (i) durante la instrucción. Puedes incluir texto formateado y enlaces.</small>
</div>
<p class="text-muted ml-2 mb-0">Por favor, asegúrate de que toda la información es correcta antes de añadir la instrucción.</p>

@push('scripts')
<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '.wysiwyg-editor',
        height: 300,
        plugins: 'advlist autolink link lists charmap preview code fullscreen table paste emoticons',
        toolbar: 'undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link | preview fullscreen',
        menubar: 'file edit view insert format tools table help',
        relative_urls: false,
        convert_urls: false,
        entity_encoding: 'raw'
    });
</script>
@endpush
