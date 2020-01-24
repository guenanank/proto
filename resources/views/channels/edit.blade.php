@extends('sb-admin')
@section('title', 'Channels')
@section('page_header', 'Channels')

@push('styles')
<link href="{{ url('css/bootstrap-select.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('channels.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit channel.</h6>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('channels.update', ['id' => $channel->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name"><strong>Name</strong></label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter channel name" value="{{ $channel->name }}">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="sub"><strong>Sub From</strong></label>
                <select name="sub" class="form-control selectpicker" id="sub" aria-describedby="subHelp" title="Select parent channel">
                    @foreach($channels as $val)
                    <option value="{{ $val->id }}" {{ $val->id == $channel->sub ? 'selected' : null }}>{!! $val->name !!}</option>
                    @endforeach
                </select>
                <small id="subHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="sort"><strong>Order</strong></label>
                <input name="sort" type="text" class="form-control" id="sort" aria-describedby="sortHelp" placeholder="Enter channel sort order" value="{{ $channel->sort }}">
                <small id="sortHelp" class="form-text text-danger"></small>
            </div>

            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0"><strong>Displayed</strong></legend>
                    <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="displayed" id="display-show" value="1" {{ !$channel->displayed ? : 'checked' }}>
                            <label class="custom-control-label" for="display-show"> Show</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="displayed" id="display-hide" value="0" {{ $channel->displayed ? : 'checked' }}>
                            <label class="custom-control-label" for="display-hide"> Hide</label>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="clearfix">&nbsp;</div>
            <p><strong>Metadata</strong></p>
            <div class="container">
                <div class="form-group">
                <label for="title">Title</label>
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for channel" value="{{ $channel->meta->title }}">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <label for="keyword">Keyword</label>
                    <input name="meta[keyword]" type="text" class="form-control" aria-describedby="metaKeywordHelp" placeholder="Keywords that describe the channel (separated by comma)" value="{{ $channel->meta->keyword }}">
                    <small id="metaKeywordHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <label for="description">Description</label>
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Channel description">{{ $channel->meta->description }}</textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <p>Cover</p>
                    <input type="file" name="cover">
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
<script src="{{ url('js/bs-custom-file-input.min.js') }}"></script>
@endpush
