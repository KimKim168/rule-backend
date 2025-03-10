@extends('admin.layouts.admin')

@section('content')
    <div class="p-4">
        <x-form-header :value="__('Create Banner')" class="p-4" />

        @livewire('banner-create')

    </div>
@endsection
