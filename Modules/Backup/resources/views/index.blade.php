@extends('backup::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('backup.name') !!}</p>
@endsection
