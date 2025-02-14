@extends('layouts.adminlte')

@section('title', 'Instrucciones')

@section('content_header')
    <x-content-header />
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>Instrucción</th>
                <th>Descripción</th>
                <th>Acción</th>
                <th>Tipo</th>
                <th>Espera</th>
                <th></th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="7">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($instructions as $instruction)
                <tr data-toggle="modal" data-target="#editModal-{{ $instruction->id }}" style="cursor: pointer;">
                    <td>{{ $instruction->instruction_name }}</td>
                    <td>{{ $instruction->tts_description }}</td>
                    <td>{{ $instruction->require_action ? 'Sí' : 'No' }}</td>
                    <td>{{ $instruction->type }}</td>
                    <td>{{ $instruction->waiting_time }}</td>
                    <td class="text-right">
                        <a href="#" class="btn btn-light btn-sm" data-toggle="modal"
                            data-target="#editModal-{{ $instruction->id }}"><i class="ri-more-fill"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-modal id="createModal" title="Añadir Instrucción" formId="create-form" action="{{ route('instructions.store') }}"
        submitText="Añadir" deleteText="Cancelar">
        @include('admin.instructions.create')
    </x-modal>

    @foreach ($instructions as $instruction)
        <x-modal id="editModal-{{ $instruction->id }}" title="Editar Instrucción" formId="edit-form-{{ $instruction->id }}"
            action="{{ route('instructions.update', $instruction->id) }}" submitText="Guardar" deleteText="Eliminar"
            deleteId="{{ $instruction->id }}" deleteName="{{ $instruction->instruction_name }}"
            deleteAction="{{ route('instructions.destroy', $instruction->id) }}">
            @include('admin.instructions.edit', ['instruction' => $instruction])
        </x-modal>
    @endforeach
    <x-footer />
@stop

@section('js')
    <script>
        window.session = {
            success: @json(session('success')),
            error: @json(session('error'))
        };
    </script>
    <script src="{{ asset('js/bundle.js') }}"></script>
@stop
