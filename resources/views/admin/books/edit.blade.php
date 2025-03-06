@extends('admin.layouts.admin')

@section('content')
    <div class="p-4">
        <x-form-header :value="__('Edit')" class="p-4" />

        @livewire('publication-edit', [
            'id' => $id
        ])

    </div>
@endsection
