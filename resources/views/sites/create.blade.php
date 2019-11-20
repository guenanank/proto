@extends('sb-admin')
@section('title', 'Sites')
@section('page_header', 'Sites')

@push('styles')
<link href="{{ url('css/bootstrap-select.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('sites.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create new site.</h6>
    </div>
    <div class="card-body">

        <form method="POST" action="{{ route('sites.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter site name">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="domain">Domain</label>
                <input name="domain" type="text" class="form-control" id="domain" aria-describedby="domainHelp" placeholder="Enter site domain url">
                <small id="domainHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="analyticsGaId">Analytics</label>
                <input name="analytics[ga_id]" type="text" class="form-control" id="analyticsGaId" aria-describedby="analyticsGaIdHelp" placeholder="Enter site google analytics id">
                <small id="analyticsGaIdHelp" class="form-text text-danger"></small>
            </div>

            <div class="clearfix"></div>
            <div class="container">
                <hr />
                <p>Metadata</p>
                <div class="form-group">
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for site">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input name="meta[keyword]" type="text" class="form-control" aria-describedby="metaKeywordHelp" placeholder="Keywords that describe the site (separated by comma)">
                    <small id="metaKeywordHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Site description"></textarea>
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
