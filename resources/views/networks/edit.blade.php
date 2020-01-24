@extends('sb-admin')
@section('title', 'Networks')
@section('page_header', 'Networks')

@push('styles')

@endpush

@section('content')
@include('components.back', ['target' => route('networks.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create new network.</h6>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('networks.update', ['id' => $network->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter network name" value="{{ $network->name }}">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="about_us">Description</label>
                <textarea name="description" class="form-control autosize" id="about_us" aria-describedby="aboutusHelp" placeholder="Enter description network">{{ $network->description }}</textarea>
            </div>

            @include('components.form')
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/autosize.js') }}"></script>
<script src="{{ url('js/bs-custom-file-input.min.js') }}"></script>
@endpush
