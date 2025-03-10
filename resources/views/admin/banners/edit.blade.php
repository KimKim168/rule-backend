@extends('admin.layouts.admin')

@section('content')
    <div class="p-4">
        <x-form-header :value="__('Edit Banner')" class="p-4" />

        @livewire('banner-edit', [
            'item' => $item,
        ])

    </div>
@endsection
