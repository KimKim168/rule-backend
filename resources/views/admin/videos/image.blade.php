@extends('admin.layouts.admin')

@section('content')
    <div class="px-4">
        <x-form-header :value="__('Add Images')" class="p-4" />

        @livewire('video-image', [
            'item' => $item,
        ])

    </div>
@endsection
