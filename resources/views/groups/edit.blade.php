@extends('sb-admin')
@section('title', 'Groups')
@section('page_header', 'Groups')

@section('content')
@include('components.back', ['target' => route('groups.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit group</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('groups.update', ['id' => $group->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
              <label for="code">Code</label>
              <input name="code" type="text" class="form-control" id="code" aria-describedby="codeHelp" placeholder="Enter group code" value="{{ $group->code }}" readonly>
              <small id="codeHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter group name" value="{{ $group->name }}">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>
            <div class="form-group">
                <label for="analyticsGaId">Analytics</label>
                <input name="analytics[gaId]" type="text" class="form-control" id="analyticsGaId" aria-describedby="analyticsGaIdHelp" placeholder="Enter group google analytics property id" value="{{ $group->analytics->has('gaId') ? $group->analytics['gaId'] : null }}">
                <small id="analyticsGaIdHelp" class="form-text text-danger"></small>
            </div>
            <div class="clearfix">&nbsp;</div>
            <div class="container">
                <hr />
                <p>Metadata</p>
                <div class="form-group">
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for group" value="{{ $group->meta->has('title') ? $group->meta['title'] : null }}">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Site description">{{ $group->meta->has('description') ? $group->meta['description'] : null }}</textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[privacy]" class="form-control autosize" aria-describedby="metaPrivacyHelp" placeholder="Group privacy rules">{{ $group->meta->has('privacy') ? $group->meta['privacy'] : null }}</textarea>
                    <small id="metaPrivacyHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[guideline]" class="form-control autosize" aria-describedby="metaGuidelineHelp" placeholder="Group cyber media guideline">{{ $group->meta->has('guideline') ? $group->meta['guideline'] : null }}</textarea>
                    <small id="metaGuidelineHelp" class="form-text text-danger"></small>
                </div>
            </div>
            @include('components.form')
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ mix('/js/autosize.js') }}"></script>
@endpush
