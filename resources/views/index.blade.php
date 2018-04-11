@extends('layout')

@section('title', 'Főoldal')

@section('content')
    <h2>Nyomtatók:</h2>
    <ul>
        @foreach ($printers as $printer)
            <li>{{ $printer->getName() }}</li>
            <li>{{ dd($printer) }}</li>
        @endforeach
    </ul>
@endsection