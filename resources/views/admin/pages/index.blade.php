@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Pages')" />
        @livewire('page-table-data')
    </div>
@endsection
