@extends('admin.layouts.admin')

@section('content')
    <div class="p-4">
        <x-form-header :value="__('Edit Item')" class="p-4" />

        @livewire('page-edit', [
            'item' => $item,
        ])

    </div>
@endsection
