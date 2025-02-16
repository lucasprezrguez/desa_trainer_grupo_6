@csrf
<div class="form-group">
    <label for="scenario_name">Nombre del Escenario</label>
    <input type="text" class="form-control" id="scenario_name" name="scenario_name">
</div>
<div class="form-group">
    <label for="image_url">URL de la Imagen</label>
    <input type="text" class="form-control" id="image_url" name="image_url">
</div>
<div class="form-group">
    <label for="instructions">Instrucciones del escenario</label>
    <select class="form-control" id="instructions" name="instructions[]" multiple>
        @foreach(App\Models\Instruction::all() as $instruction)
            <option value="{{ $instruction->instruction_id }}">{{ $instruction->instruction_name }}</option>
        @endforeach
    </select>
</div>
