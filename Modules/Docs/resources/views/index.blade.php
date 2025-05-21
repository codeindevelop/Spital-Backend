@extends('docs::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('docs.name') !!}</p>
@endsection
