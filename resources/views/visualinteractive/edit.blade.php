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
        <h6 class="m-0 font-weight-bold text-primary">Edit Visual Interactive.</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('visualinteractives.update', ['id' => $visualinteractive->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="name"><strong>Name</strong></label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="titleHelp" placeholder="Enter Name of Visual Interactive" value="{{ $visualinteractive->name }}">
                <small id="titleHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
            <label for="cover"><strong>Change File Zip Here</strong></label>
                <input class="form-control-file fileinput" name="file" type="file" />
                <span id="meta">{{ json_encode($visualinteractive->cover) }}</span>
            </div>
            <div class="form-group">
            <label for="cover"><strong>Change Cover Here</strong></label>
                <input class="form-control-file imageinput" name="image" type="file" />
                <span id="meta">{{ json_encode($visualinteractive->cover) }}</span>
            </div>
            <label for="name"><strong>Meta Visual Interactive</strong></label>
            </hr>
            <div class="container">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="meta[title]" type="text" class="form-control" id="title" aria-describedby="titleHelp" placeholder="Enter images title" value="{{ $visualinteractive->meta->title }}">
                    <small id="titleHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="caption">Keyword</label>
                    <input name="meta[keyword]" type="text" class="form-control" id="caption" aria-describedby="captionHelp" placeholder="Enter images caption" value="{{ $visualinteractive->meta->keyword }}">
                    <small id="captionHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="descriptionHelp" placeholder="Enter images description">{{ $visualinteractive->meta->description }}</textarea>
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
    $('span#meta').hide()
    var images = JSON.parse($('span#meta').html());
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
        dropZoneEnabled : false,
        overwriteInitial: true,
        initialCaption: [images.file.filename],
        initialPreview: [images.file.path_file_zip],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
                caption: images.name,
                filename: images.file.filename,
                downloadUrl: images.file.path_file_zip,
                size: images.file.size,
                key: 1
            },
        ]
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
        overwriteInitial: true,
        initialCaption: [images.image.filename],
        initialPreview: [images.image.path_image],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
                caption: images.name,
                filename: images.image.filename,
                downloadUrl: images.image.path_image,
                size: images.image.size,
                width: images.image.dimension.width,
                key: 2
            }
        ]
    });
</script>
@endpush
