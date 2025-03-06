@extends('admin.layouts.admin')
@section('content')
    <div>
        <x-page-header :value="__('Categories')" />
        @livewire('book-category-table-data')
    </div>
@endsection
