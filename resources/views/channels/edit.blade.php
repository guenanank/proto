@extends('sb-admin')
@section('title', 'Channels')
@section('page_header', 'Channels')

@push('styles')
<link href="{{ mix('/css/bootstrap-select.css') }}" rel="stylesheet" />
<link href="{{ mix('/css/fileinput.css') }}" rel="stylesheet" />
@endpush

@section('content')
@include('components.back', ['target' => route('channels.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Edit channel</h6>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('channels.update', ['id' => $channel->id]) }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="form-group">
                <label for="mediaId">Media</label>
                <select name="mediaId" class="form-control selectpicker" id="mediaId" aria-describedby="mediaIdHelp" title="Select media" data-live-search="true">
                    @foreach($groups as $group)
                    <optgroup label="{{ $group->name }}">
                        @foreach($group->media as $media)
                        <option {{ $media->id == $channel->mediaId ? 'selected' : null }} value="{{ $media->id }}" data-subtext="{{ $media->meta['title'] }}">{{ $media->name }}</option>
                        @endforeach
                    </optgroup>
                    @endforeach
                </select>
            </div>
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
<<<<<<< HEAD
            <div class="form-group">
                <label for="analyticsViewId">Analytics</label>
                <input name="analytics[viewId]" type="text" class="form-control" id="analyticsViewId" aria-describedby="analyticsViewIdHelp" placeholder="Enter channel google analytics id" value="{{ $channel->analytics['viewId'] }}">
                <small id="analyticsViewIdHelp" class="form-text text-danger"></small>
            </div>
=======
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
            <div class="clearfix">&nbsp;</div>
            <p><strong>Metadata</strong></p>
            <div class="container">
                <div class="form-group">
<<<<<<< HEAD
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for channel" value="{{ $channel->meta->has('title') ? $channel->meta['title'] : null }}">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input name="meta[keywords]" type="text" class="form-control" aria-describedby="metaKeywordsHelp" placeholder="Keywords that describe the channel (separated by comma)" value="{{ $channel->meta->has('keywords') ? $channel->meta['keywords'] : null }}">
                    <small id="metaKeywordsHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Channel description">
                      {{ $channel->meta->has('description') ? $channel->meta['description'] : null }}
                    </textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input type="hidden" name="meta[cover]" />
                    <input type="file" name="cover" class="custom-file-input fileInput" aria-describedby="metaCoverHelp" data-msg-placeholder="Chose cover file">
                    <small id="metaCoverHelp" class="form-text text-danger"></small>
=======
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
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
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
<script>
    // $('#media-id').on('changed.bs.select', function(e, clickedIndex, isSelected, previousValue) {
    //     var _id = $(this).val();
    //     $('#sub option').find('#' + _id).hide().selectpicker('refresh');
    // });

    $('.fileInput').fileinput({
      showPreview: false
    });
</script>
@endpush
