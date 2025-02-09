@extends('layouts.adminlte')

@section('title', 'Instrucciones')

@section('content_header')
    <x-content-header />
@stop

@section('content')
    <table class="table table-borderless table-hover results">
        <thead>
            <tr>
                <th>#</th>
                <th>Nombre de Instrucción</th>
                <th>Descripción TTS</th>
                <th>Tipo</th>
                <th class="text-right">Acciones</th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="5">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($instructions as $instruction)
                <tr style="cursor: pointer;">
                    <td>{{ $instruction->id }}</td>
                    <td>{{ $instruction->instruction_name }}</td>
                    <td>{{ $instruction->tts_description }}</td>
                    <td>{{ $instruction->type }}</td>
                    <td class="text-right">
                        <a href="#" class="" data-toggle="modal" data-target="#editModal-{{ $instruction->id }}">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <x-modal id="createModal" title="Añadir Instrucción" formId="create-form" action="{{ route('instructions.store') }}" submitText="Añadir" deleteText="Cancelar">
        @include('admin.instructions.create')
    </x-modal>

    @foreach ($instructions as $instruction)
        <x-modal id="editModal-{{ $instruction->id }}" title="Editar Instrucción" formId="edit-form-{{ $instruction->id }}" action="{{ route('instructions.update', $instruction->id) }}" submitText="Guardar" deleteText="Eliminar" deleteId="{{ $instruction->id }}" deleteName="{{ $instruction->instruction_name }}" deleteAction="{{ route('instructions.destroy', $instruction->id) }}">
            @include('admin.instructions.edit', ['instruction' => $instruction])
        </x-modal>
    @endforeach
@stop

@section('footer')
    <x-footer />
@stop

@section('js')
    <script src="{{ asset('js/bundle.js') }}"></script>
@stop
