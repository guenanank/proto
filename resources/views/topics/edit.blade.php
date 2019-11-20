@extends('sb-admin')
@section('title', 'Topics')
@section('page_header', 'Topics')

@push('styles')
<link href="{{ url('css/bootstrap-select.css') }}" rel="stylesheet" />
<link href="{{ url('css/tempusdominus-bootstrap-4.min.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('topics.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit topic.</h6>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('topics.update', ['id' => $topic->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="title">Title</label>
                <input name="title" type="text" class="form-control" id="title" aria-describedby="titleHelp" placeholder="Enter topic title" value="{{ $topic->title }}">
                <small id="titleHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="published">Published</label>
                <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datetimepicker" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                    <input name="published" type="text" class="form-control datetimepicker-input" id="published" aria-describedby="publishedHelp" placeholder="Enter topic published" data-target="#datetimepicker" value="{{ $topic->published }}">
                </div>
                <small id="publishedHelp" class="form-text text-danger"></small>
            </div>

            <div class="clearfix">&nbsp;</div>
            <div class="clearfix">&nbsp;</div>
            <div class="container">
                <p>Metadata</p>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Topic description">{{ $topic->meta->description }}</textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="custom-file">
                    <input type="hidden" name="meta[cover]" />
                    <input type="file" name="cover" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
            </div>

            @include('components.form')
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/autosize.js') }}"></script>
<script src="{{ url('js/bootstrap-select.js') }}"></script>
<script src="{{ url('js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script src="{{ url('js/bs-custom-file-input.min.js') }}"></script>
<script>
    $(function() {
        $('#datetimepicker').datetimepicker({
            format: 'YYYY-MM-DD HH:mm:ss'
        });
    });
</script>
@endpush
