@extends('sb-admin')
@section('title', 'Media')
@section('page_header', 'Media')

@push('styles')
<link href="{{ mix('/css/bootstrap-select.css') }}" rel="stylesheet" />
<link href="{{ mix('/css/colorpicker.css') }}" rel="stylesheet" />
<link href="{{ mix('/css/fileinput.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('media.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create new media</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('media.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="groupId">Group</label>
                <select name="groupId" class="form-control selectpicker" id="groupId" aria-describedby="groupIdHelp" title="Select Group">
                    @foreach($groups as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <small id="groupIdHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter media name">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="domain">Domain</label>
                <input name="domain" type="text" class="form-control" id="domain" aria-describedby="domainHelp" placeholder="Enter media domain url">
                <small id="domainHelp" class="form-text text-danger"></small>
            </div>
            <div class="clearfix">&nbsp;</div>
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="meta-tab" data-toggle="tab" href="#meta" role="tab" aria-controls="meta" aria-selected="true">Metadata</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="analytics-tab" data-toggle="tab" href="#analytics" role="tab" aria-controls="analytics" aria-selected="false">Analytics</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="assets-tab" data-toggle="tab" href="#assets" role="tab" aria-controls="assets" aria-selected="false">Assets</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="masthead-tab" data-toggle="tab" href="#masthead" role="tab" aria-controls="masthead" aria-selected="false">Masthead</a>
                </li>
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="meta" role="tabpanel" aria-labelledby="meta-tab">
                    <div class="clearfix">&nbsp;</div>
                    <div class="container">
                        <div class="form-group">
                            <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for media">
                            <small id="metaTitleHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input name="meta[keywords]" type="text" class="form-control" aria-describedby="metaKeywordsHelp" placeholder="Keywords that describe the media (separated by comma)">
                            <small id="metaKeywordsHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input name="meta[color]" type="text" class="form-control colorPicker" aria-describedby="metaColorHelp" placeholder="Color for site">
                            <small id="metaColorHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Site description"></textarea>
                            <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="analytics" role="tabpanel" aria-labelledby="analytics-tab">
                    <div class="clearfix">&nbsp;</div>
                    <div class="container">
                        <div class="form-group">
                            <input name="analytics[gaId]" type="text" class="form-control" id="analyticsGaId" aria-describedby="analyticsGaIdHelp" placeholder="Enter media google analytics view id">
                            <small id="analyticsGaIdHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input name="analytics[youtubeChannel]" type="text" class="form-control" id="analyticsYoutubeChannel" aria-describedby="analyticsYoutubeChannelHelp" placeholder="Enter media youtube channel">
                            <small id="analyticsYoutubeChannelHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="assets" role="tabpanel" aria-labelledby="assets-tab">
                    <div class="clearfix">&nbsp;</div>
                    <div class="container">
                        <div class="form-group">
                            <input type="hidden" name="assets[logo]" />
                            <input type="file" name="logo" class="form-control-file fileInput" aria-describedby="assetsLogoHelp" data-msg-placeholder="Chose main logo" data-allowed-file-types="image">
                            <small id="assetsLogoHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="assets[logoAlt]" />
                            <input type="file" name="logoAlt" class="form-control-file fileInput" aria-describedby="assetsLogoAltHelp" data-msg-placeholder="Chose logo alternate" data-allowed-file-types="image">
                            <small id="assetsLogoAltHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="assets[icon]" />
                            <input type="file" name="icon" class="form-control-file fileInput" aria-describedby="assetsIconHelp" data-msg-placeholder="Chose icon" data-allowed-file-types="image">
                            <small id="assetsIconHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="assets[css]" />
                            <input type="file" name="css" class="form-control-file fileInput" aria-describedby="assetsCssHelp" data-msg-placeholder="Chose file css" data-allowed-file-extension="css">
                            <small id="assetsCssHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <input type="hidden" name="assets[js]" />
                            <input type="file" name="js" class="form-control-file fileInput" aria-describedby="assetsJsHelp" data-msg-placeholder="Chose file js" data-allowed-file-extension="js">
                            <small id="assetsJsHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="masthead" role="tabpanel" aria-labelledby="masthead-tab">
                    <div class="clearfix">&nbsp;</div>
                    <div class="container">
                        <div class="form-group">
                            <textarea name="masthead[about]" class="form-control autosize" aria-describedby="mastheadAboutHelp" placeholder="Enter about media"></textarea>
                            <small id="mastheadAboutHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <textarea name="masthead[editorial]" class="form-control autosize" aria-describedby="mastheadEditorialHelp" placeholder="Enter media editorial"></textarea>
                            <small id="mastheadEditorialHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <textarea name="masthead[management]" class="form-control autosize" aria-describedby="mastheadManagementHelp" placeholder="Enter media management"></textarea>
                            <small id="mastheadManagementHelp" class="form-text text-danger"></small>
                        </div>
                        <div class="form-group">
                            <textarea name="masthead[contact]" class="form-control autosize" aria-describedby="mastheadContactHelp" placeholder="Enter media contact"></textarea>
                            <small id="mastheadContactHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
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
<script src="{{ mix('/js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ mix('/js/fileinput.js') }}"></script>
<script src="{{ mix('/js/fileinput-fa.js') }}"></script>
@endpush
