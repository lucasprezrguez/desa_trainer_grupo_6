@csrf
@method('PUT')
<div class="form-group">
    <label for="name">Nombre</label>
    <input type="text" name="name" value="{{ $user->name }}" class="form-control" required>
</div>
<div class="form-group">
    <label for="email">Correo Electrónico</label>
    <input type="email" name="email" value="{{ $user->email }}" class="form-control" required>
</div>
<div class="form-group">
    <label for="roles">Perfil</label>
    <select name="roles" class="form-control custom-select" required>
        <option value="alumno" {{ $user->roles == 'alumno' ? 'selected' : '' }}>Alumno</option>
        <option value="profesor" {{ $user->roles == 'profesor' ? 'selected' : '' }}>Profesor</option>
        <option value="admin" {{ $user->roles == 'admin' ? 'selected' : '' }}>Administrador</option>
    </select>
</div>