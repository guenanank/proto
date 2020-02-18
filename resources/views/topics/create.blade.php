@extends('sb-admin')
@section('title', 'Topics')
@section('page_header', 'Topics')

@push('styles')
<link href="{{ mix('/css/bootstrap-select.css') }}" rel="stylesheet" />
<link href="{{ mix('/css/fileinput.css') }}" rel="stylesheet" />
<link href="{{ mix('/css/bootstrap-datetimepicker.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('topics.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create new topic</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('topics.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="mediaId">Media</label>
                <select name="mediaId" class="form-control selectpicker" id="mediaId" aria-describedby="mediaIdHelp" title="Select media" data-live-search="true">
                    @foreach($groups as $group)
                    <optgroup label="{{ $group->name }}">
                        @foreach($group->media as $media)
                        <option value="{{ $media->id }}" data-subtext="{{ $media->meta['title'] }}">{{ $media->name }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="title">Title</label>
                <input name="title" type="text" class="form-control" id="title" aria-describedby="titleHelp" placeholder="Enter topic title">
                <small id="titleHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="published">Published</label>
                <div class="input-group date" id="datetimepicker" data-target-input="nearest">
                    <div class="input-group-prepend" data-target="#datetimepicker" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar-alt"></i></div>
                    </div>
                    <input name="published" type="text" class="form-control datetimepicker-input" id="published" aria-describedby="publishedHelp" placeholder="Enter topic published" data-target="#datetimepicker">
                </div>
                <small id="publishedHelp" class="form-text text-danger"></small>
            </div>
            <div class="clearfix"></div>
            <div class="container">
                <hr />
                <p>Metadata</p>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Topic description"></textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input type="hidden" name="meta[cover]" />
                    <input type="file" name="cover" class="custom-file-input fileInput" aria-describedby="metaCoverHelp" data-msg-placeholder="Chose cover file">
                    <small id="metaCoverHelp" class="form-text text-danger"></small>
                </div>
            </div>
            @include('components.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('/js/autosize.js') }}"></script>
<script src="{{ mix('/js/bootstrap-select.js') }}"></script>
<script src="{{ mix('/js/fileinput.js') }}"></script>
<script src="{{ mix('/js/fileinput-fa.js') }}"></script>
<script src="{{ mix('/js/bootstrap-datetimepicker.js') }}"></script>

@endpush
