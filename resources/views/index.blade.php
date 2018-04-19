@extends('layout')

@section('title', 'Főoldal')

@section('content')
    <h2>Nyomtatók:</h2>
    @include('datatables::datatable.datatable_html', ['datatable' => $dataTable])
@endsection

@push('scripts')
    @include('datatables::datatable.datatable_js', ['datatable' => $dataTable])
@endpush