@extends('adminlte::page')

@section('title', 'Custom AdminLTE Title')

@section('content_header')
    <h1>Custom AdminLTE Header</h1>
@endsection

@section('content')
    <p>Custom AdminLTE content goes here.</p>
@endsection

@section('css')
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.css" />
@endsection

