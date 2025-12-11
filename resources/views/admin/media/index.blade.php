@extends('layouts.admin')

@section('title', 'Media Management')
@section('page-title', 'Media Management')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            @livewire('admin.media-management')
        </div>
    </div>
</div>
@endsection
