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