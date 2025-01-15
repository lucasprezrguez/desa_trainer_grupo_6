@extends('layouts.adminlte')

@section('title', 'Crear Dispositivo')

@section('content_header')
    <h1>Crear Nuevo Dispositivo</h1>
@stop

@section('content')
    <form action="{{ route('devices.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="name">Nombre del Dispositivo</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="on_led">LED Encendido</label>
            <select class="form-control" id="on_led" name="on_led">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="pause_state">Estado de Pausa</label>
            <select class="form-control" id="pause_state" name="pause_state">
                <option value="1">Sí</option>
                <option value="0">No</option>
            </select>
        </div>
        <div class="form-group">
            <label for="display_message">Mensaje de Display</label>
            <textarea class="form-control" id="display_message" name="display_message"></textarea>
        </div>
        <div class="form-group">
            <label for="image">Imagen de Fondo</label>
            <input type="file" class="form-control-file" id="image" name="image" accept="image/*">
        </div>
        <div class="form-group mt-4">
            <button type="submit" class="btn btn-success">Crear Dispositivo</button>
        </div>
    </form>
@stop