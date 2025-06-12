@extends('files::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('files.name') !!}</p>
@endsection
