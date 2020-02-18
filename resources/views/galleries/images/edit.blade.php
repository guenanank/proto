@extends('sb-admin')
@section('title', 'Image Galleries')
@section('page_header', 'Image Galleries')

@push('styles')
<link href="{{ mix('/css/fileinput.css') }}" rel="stylesheet" />
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
                    <label for="caption">Caption</label>
                    <input name="meta[caption]" type="text" class="form-control" id="caption" aria-describedby="captionHelp" placeholder="Enter images caption" value="{{ $gallery->meta['caption'] }}">
                    <small id="captionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <label for="photographer">Credit</label>
                    <input name="meta[credit]" type="text" class="form-control" id="credit" aria-describedby="creditHelp" placeholder="Enter images credit" value="{{ $gallery->meta['credit'] }}">
                    <small id="creditHelp" class="form-text text-danger"></small>
                </div>
            </div>

            @include('components.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('/js/autosize.js') }}"></script>
<script src="{{ mix('/js/fileinput.js') }}"></script>
<script src="{{ mix('/js/fileinput-fa.js') }}"></script>
<script>
    $('span#meta').hide();
    const path = 'https://gridnetwork.s3-ap-southeast-1.amazonaws.com/';
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
        initialCaption: images.caption,
        initialPreview: [path + images.path],
        initialPreviewAsData: true,
        initialPreviewConfig: [{
                caption: images.caption,
                filename: images.filename,
                downloadUrl: path + images.path,
                size: images.size,
                width: images.dimension.width,
                key: 1
            }
        ],
    });
</script>
@endpush
