@extends('layouts.adminlte')

@section('title', 'Instrucciones')

@section('content_header')
    @component('components.content_header', ['hideAddButton' => true])
    @endcomponent
@stop

@section('content')
    <table class="table table-hover results">
        <thead>
            <tr>
                <th>#</th>
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
                    <td>{{ $instruction->instruction_id }}</td>
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

    <!-- <x-modal id="createModal" title="Añadir Instrucción" formId="create-form" action="{{ route('instructions.store') }}"
        submitText="Añadir" deleteText="Cancelar">
        @include('admin.instructions.create')
    </x-modal> -->

    @foreach ($instructions as $instruction)
        <x-modal id="editModal-{{ $instruction->instruction_id }}" title="Editar Instrucción" formId="edit-form-{{ $instruction->instruction_id }}"
            action="{{ route('instructions.update', $instruction->instruction_id) }}" submitText="Guardar" deleteText="Eliminar"
            deleteId="{{ $instruction->instruction_id }}" deleteName="{{ $instruction->instruction_name }}"
            deleteAction="{{ route('instructions.destroy', $instruction->instruction_id) }}">
            @include('admin.instructions.edit', ['instruction' => $instruction])
        </x-modal>
    @endforeach

    <!-- Modales para TinyMCE -->
    <div class="modal fade" id="linkModal" tabindex="-1" role="dialog" aria-labelledby="linkModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="linkModalLabel">Insertar Enlace</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="linkUrl">URL del enlace</label>
                        <input type="url" class="form-control" id="linkUrl" placeholder="https://...">
                    </div>
                    <div class="form-group">
                        <label for="linkText">Texto del enlace</label>
                        <input type="text" class="form-control" id="linkText" placeholder="Texto a mostrar">
                    </div>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="linkNewTab" checked>
                        <label class="form-check-label" for="linkNewTab">Abrir en nueva pestaña</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="insertLinkBtn">Insertar</button>
                </div>
            </div>
        </div>
    </div>
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
    <!-- Add TinyMCE -->
    <script src="https://cdn.tiny.cloud/1/s1zgbalfhihcr943y2rc256lvsjwz8jjpg03qctot2oyrjap/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        $(document).ready(function() {
            var activeEditor = null;
            
            $('.modal').on('shown.bs.modal', function() {
                tinymce.init({
                    selector: '.wysiwyg-editor',
                    height: 300,
                    plugins: 'autolink lists fullscreen',
                    toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | insertlink | code fullscreen',
                    menubar: false,
                    convert_urls: false,
                    setup: function(editor) {
                        editor.on('change', function() {
                            editor.save();
                        });
                        
                        // Botón personalizado para insertar enlace
                        editor.ui.registry.addButton('insertlink', {
                            icon: 'link',
                            tooltip: 'Insertar enlace',
                            onAction: function() {
                                activeEditor = editor;
                                
                                // Obtener el texto seleccionado
                                var selectedText = editor.selection.getContent({format: 'text'});
                                $('#linkText').val(selectedText);
                                
                                // Mostrar el modal
                                $('#linkModal').modal('show');
                            }
                        });
                    }
                });
            });

            $('#insertLinkBtn').on('click', function() {
                if (!activeEditor) return;
                
                var url = $('#linkUrl').val();
                var text = $('#linkText').val();
                var newTab = $('#linkNewTab').is(':checked') ? ' target="_blank"' : '';
                
                if (url && url.trim() !== '') {
                    if (!text || text.trim() === '') {
                        text = url;
                    }
                    
                    activeEditor.insertContent('<a href="' + url + '"' + newTab + '>' + text + '</a>');
                    $('#linkModal').modal('hide');
                }
            });

            $('form').submit(function() {
                if (tinymce.activeEditor) {
                    tinymce.activeEditor.save();
                }
                return true;
            });
            
            $('.modal').on('hidden.bs.modal', function() {
                tinymce.remove('.wysiwyg-editor');
            });
        });
    </script>
@stop
