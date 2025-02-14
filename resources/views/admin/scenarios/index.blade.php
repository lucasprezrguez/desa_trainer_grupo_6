@extends('layouts.adminlte')

@section('title', 'Escenarios')

@section('content_header')
    <x-content-header />
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>Escenario</th>
                <th>Códigos de los escenarios</th>
                <th></th>
            </tr>
            <tr class="warning no-result" style="display:none;">
                <td colspan="4">No hay resultados.</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($scenarios as $scenario)
                <tr data-toggle="modal" data-target="#editModal-{{ $scenario->id }}" style="cursor: pointer;">
                    <td>{{ $scenario->scenario_name }}</td>
                    <td><img src="{{ asset($scenario->image_url) }}" alt="Código del escenario" style="width: auto; height: 36px;"></td>
                    <td class="text-right">
                        <a href="#" class="btn btn-light btn-sm" data-toggle="modal"
                            data-target="#editModal-{{ $scenario->id }}"><i class="ri-more-fill"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <x-modal id="createModal" title="Añadir Escenario" formId="create-form" action="{{ route('scenarios.store') }}"
        submitText="Añadir" deleteText="Cancelar">
        @include('admin.scenarios.create')
    </x-modal>

    @foreach ($scenarios as $scenario)
        <x-modal id="editModal-{{ $scenario->id }}" title="Editar Escenario" formId="edit-form-{{ $scenario->id }}"
            action="{{ route('scenarios.update', $scenario->id) }}" submitText="Guardar" deleteText="Eliminar"
            deleteId="{{ $scenario->id }}" deleteName="{{ $scenario->scenario_name }}"
            deleteAction="{{ route('scenarios.destroy', $scenario->id) }}">
            @include('admin.scenarios.edit', ['scenario' => $scenario])
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
