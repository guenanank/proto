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
        <h6 class="m-0 font-weight-bold text-primary">Create new channel.</h6>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('channels.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter channel name">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="name">Sub From</label>
                <select name="sub" class="form-control selectpicker" id="sub" aria-describedby="subHelp" title="Select parent channel">
                    @foreach($channels as $channel)
                    <option value="{{ $channel->id }}">{!! $channel->name !!}</option>
                    @endforeach
                </select>
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="sort">Order</label>
                <input name="sort" type="text" class="form-control" id="sort" aria-describedby="sortHelp" placeholder="Enter channel sort order">
                <small id="sortHelp" class="form-text text-danger"></small>
            </div>

            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-2 pt-0">Displayed</legend>
                    <div class="col-sm-10">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="displayed" id="display-show" value="1" checked>
                            <label class="custom-control-label" for="display-show"> Show</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="displayed" id="display-hide" value="0">
                            <label class="custom-control-label" for="display-hide"> Hide</label>
                        </div>
                    </div>
                </div>
            </fieldset>

            <div class="form-group">
                <label for="analyticsGaId">Analytics</label>
                <input name="analytics[ga_id]" type="text" class="form-control" id="analyticsGaId" aria-describedby="analyticsGaIdHelp" placeholder="Enter channel google analytics id">
                <small id="analyticsGaIdHelp" class="form-text text-danger"></small>
            </div>

            <div class="clearfix"></div>
            <div class="container">
                <hr />
                <p>Metadata</p>
                <div class="form-group">
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for channel">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input name="meta[keyword]" type="text" class="form-control" aria-describedby="metaKeywordHelp" placeholder="Keywords that describe the channel (separated by comma)">
                    <small id="metaKeywordHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Channel description"></textarea>
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
<script src="{{ url('js/bs-custom-file-input.min.js') }}"></script>
@endpush
