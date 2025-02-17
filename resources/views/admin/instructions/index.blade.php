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
                <th class="text-center">Requiere Acción</th>
                <th class="text-center">Espera (s)</th>
                <th></th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($instructions as $instruction)
                <tr data-toggle="modal" data-target="#editModal-{{ $instruction->instruction_id }}" style="cursor: pointer;">
                    <td>{{ $instruction->instruction_name }}</td>
                    <td class="text-center">{{ $instruction->require_action ? 'Sí' : 'No' }}</td>
                    <td class="text-center">{{ $instruction->waiting_time }}</td>
                    <td class="text-right">
                        <a href="#" class="btn btn-light btn-sm" data-toggle="modal"
                            data-target="#editModal-{{ $instruction->instruction_id }}"><i class="ri-more-fill"></i></a>
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
        <x-modal id="editModal-{{ $instruction->instruction_id }}" title="Editar Instrucción" formId="edit-form-{{ $instruction->instruction_id }}"
            action="{{ route('instructions.update', $instruction->instruction_id) }}" submitText="Guardar" deleteText="Eliminar"
            deleteId="{{ $instruction->instruction_id }}" deleteName="{{ $instruction->instruction_name }}"
            deleteAction="{{ route('instructions.destroy', $instruction->instruction_id) }}">
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
