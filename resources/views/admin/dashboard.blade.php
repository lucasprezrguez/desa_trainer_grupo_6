@extends('layouts.adminlte')

@section('title', 'Panel de Administración')

@section('content_header')
    <h1>Panel de Administración</h1>
@endsection

@section('content')
    <div>
            <form action="{{ route('update.bpm') }}" method="POST" id="bpm-form">
                @csrf
                <input type="hidden" name="bpm" id="selected-bpm" value="{{ session('metronome_bpm', 110) }}">
                
                <div class="form-group">
                    <label class="mb-3">CPM del Metrónomo:</label>
                    <div class="btn-group w-100" role="group">
                        @foreach([100, 110, 120] as $bpm)
                            <button 
                                type="button"
                                class="btn btn-outline-primary {{ session('metronome_bpm', 110) == $bpm ? 'active' : '' }}"
                                data-bpm="{{ $bpm }}"
                                onclick="document.getElementById('selected-bpm').value = this.dataset.bpm; document.getElementById('bpm-form').submit()"
                            >
                                {{ $bpm }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
@endsection

<style>
    .btn-group .btn {
        flex: 1 1 auto;
    }
    .btn.active {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>