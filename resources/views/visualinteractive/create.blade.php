@extends('sb-admin')
@section('title', 'Visual Interactive')
@section('page_header', 'Visual Interactive')

@push('styles')
<link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
<!-- <link rel="stylesheet" href="{{ url('css/fileinput-rtl.min.css') }}"> -->
@endpush

@section('content')
@include('components.back', ['target' => route('visualinteractives.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Upload new Visual Interactive.</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('visualinteractives.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="titleHelp" placeholder="Enter Name of Visual Interactive">
                <small id="titleHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
            <label for="cover"><strong>Insert File Zip Here</strong></label>
                <input class="form-control-file fileinput" name="file" type="file" required/>
            </div>
            <div class="form-group">
            <label for="cover"><strong>Insert Cover Here</strong></label>
                <input class="form-control-file imageinput" name="image" type="file" required/>
            </div>
            <div class="container">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="meta[title]" type="text" class="form-control" id="title" aria-describedby="titleHelp" placeholder="Enter Visual Interactive title">
                    <small id="titleHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="keyword">Keyword</label>
                    <input name="meta[keyword]" type="text" class="form-control" id="keyword" aria-describedby="keywordHelp" placeholder="Enter Visual Interactive Keyword">
                    <small id="keywordHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="descriptionHelp" placeholder="Enter Visual Interactive description"></textarea>
                    <small id="descriptionHelp" class="form-text text-danger"></small>
                </div>
            </div>

            @include('components.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/fileinput.min.js') }}"></script>
<script src="{{ url('js/fileinput-fa-theme.min.js') }}"></script>
<script src="{{ url('js/autosize.js') }}"></script>
<script>
    $('.fileinput').fileinput({
        theme: 'fas',
        browseLabel: 'Find',
        browseIcon: '<i class=\"fa fa-search\"></i>',
        previewClass: 'bg-gray-100',
        showRemove: false,
        showUpload: false,
        maxFileCount: 5,
        maxFileSize: 2048,
        allowedFileTypes: 'zip,rar,ZIP,RAR',
        dropZoneEnabled : false
    });

    $('.imageinput').fileinput({
        theme: 'fas',
        browseLabel: 'Find',
        browseIcon: '<i class=\"fa fa-search\"></i>',
        previewClass: 'bg-gray-100',
        showRemove: false,
        showUpload: false,
        maxFileCount: 5,
        maxFileSize: 2048,
        allowedFileTypes: ['image'],
    });
</script>
@endpush
