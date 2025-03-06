@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Products')" />
        @livewire('publication-table-data')
    </div>
@endsection
