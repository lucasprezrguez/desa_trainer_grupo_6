@props(['id', 'title', 'formId', 'action', 'submitText', 'deleteText', 'deleteId' => null, 'deleteName' => null, 'deleteAction' => null, 'resetPassword' => null])

@php
$id = $id ?? md5($attributes->wire('model'));
@endphp

<div class="modal fade" id="{{ $id }}" tabindex="-1" role="dialog" aria-labelledby="{{ $id }}Label" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}Label">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="{{ $formId }}" action="{{ $action }}" method="POST">
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer d-flex justify-content-between">
                    <div>
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('{{ $formId }}').submit();">{{ $submitText }}</button>
                        @if(isset($resetPassword))
                            <button type="button" class="btn btn-link"  data-user-id="{{ $resetPassword }}" onclick="generatePassword({{ $resetPassword }}); return false;"><u>Resetear contraseña</u></button>
                        @endif
                    </div>
                    @if(isset($deleteId) && isset($deleteName) && isset($deleteAction))
                        <button type="button" class="btn btn-light text-danger" onclick="deleteItem({{ $deleteId }}, '{{ $deleteName }}');">{{ $deleteText }}</button>
                    @endif
                </div>
            </form>
            @if(isset($deleteId) && isset($deleteAction))
                <form id="delete-form-{{ $deleteId }}" action="{{ $deleteAction }}" method="POST" style="display:none;">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        </div>
    </div>
</div>