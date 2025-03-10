@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Banners')" />
        @livewire('banner-table-data')
    </div>
@endsection
