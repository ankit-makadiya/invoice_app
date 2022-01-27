@extends('adminlte::page')

@section('title', 'Dashboard | ' . env("APP_NAME"))

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <p>Welcome to Invoice App.</p>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop