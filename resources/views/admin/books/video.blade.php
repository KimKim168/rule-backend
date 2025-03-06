@extends('admin.layouts.admin')

@section('content')
    <div class="p-4">
        <x-form-header :value="__('Add Videos')" class="p-4" />

        @livewire('book-video', [
            'item' => $item,
        ])

    </div>
@endsection
