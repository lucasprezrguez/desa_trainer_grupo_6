@csrf
<div class="form-group">
    <label for="name">Nombre</label>
    <input type="text" name="name" class="form-control" required>
</div>
<div class="form-group">
    <label for="email">Correo Electrónico</label>
    <input type="email" name="email" class="form-control" required>
</div>
<div class="form-group">
    <label for="roles">Perfil</label>
    <select name="roles" class="form-control custom-select" required>
        <option value="alumno" selected>Alumno</option>
        <option value="profesor">Profesor</option>
        <option value="admin">Administrador</option>
    </select>
</div>
<p class="text-muted ml-2 mb-0">El nombre de usuario y la contraseña se enviarán a la dirección de correo electrónico indicada arriba.</p>