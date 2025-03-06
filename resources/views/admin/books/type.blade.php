@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Types')" />
        @livewire('publication-type-table-data')
    </div>
@endsection
