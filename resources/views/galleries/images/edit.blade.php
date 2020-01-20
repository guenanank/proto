@extends('sb-admin')
@section('title', 'Image Galleries')
@section('page_header', 'Image Galleries')

@push('styles')
<link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
<!-- <link rel="stylesheet" href="{{ url('css/fileinput-rtl.min.css') }}"> -->
@endpush

@section('content')
@include('components.back', ['target' => route('galleries', ['type' => 'images'])])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit image.</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('galleries.update', ['type' => 'images', 'id' => $gallery->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <input type="hidden" name="type" value="images" />
            <div class="form-group">
                <input class="form-control-file fileinput" name="files[]" multiple type="file" />
                <span id="meta">{{ json_encode($gallery->meta) }}</span>
            </div>
            <div class="container">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input name="meta[title]" type="text" class="form-control" id="title" aria-describedby="titleHelp" placeholder="Enter images title" value="{{ $gallery->meta->title }}">
                    <small id="titleHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="caption">Caption</label>
                    <input name="meta[caption]" type="text" class="form-control" id="caption" aria-describedby="captionHelp" placeholder="Enter images caption" value="{{ $gallery->meta->caption }}">
                    <small id="captionHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="descriptionHelp" placeholder="Enter images description">{{ $gallery->meta->description }}</textarea>
                    <small id="descriptionHelp" class="form-text text-danger"></small>
                </div>

                <div class="form-group">
                    <label for="photographer">Photographer/Credit</label>
                    <input name="meta[photographer]" type="text" class="form-control" id="photographer" aria-describedby="photographerHelp" placeholder="Enter images photographer or credit" value="{{ $gallery->meta->photographer }}">
                    <small id="photographerHelp" class="form-text text-danger"></small>
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
    console.log(images);
    $('.fileinput').fileinput({
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
        initialCaption: images.filename,
        initialPreview: [images.path],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
                caption: images.title,
                filename: images.filename,
                downloadUrl: images.path,
                size: images.size,
                width: images.dimension.width,
                key: 1
            }
        ],
    });
</script>
@endpush
