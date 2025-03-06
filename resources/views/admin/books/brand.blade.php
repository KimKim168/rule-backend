@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Brands')" />
        @livewire('brand-table-data')
    </div>
@endsection
