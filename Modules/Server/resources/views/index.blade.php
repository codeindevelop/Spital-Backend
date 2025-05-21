@extends('server::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('server.name') !!}</p>
@endsection
