@extends('sb-admin')
@section('title', 'Channels')
@section('page_header', 'Channels')

@push('styles')
<link href="{{ url('css/bootstrap-select.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ url('css/fileinput.css') }}">
@endpush

@section('content')
@include('components.back', ['target' => route('channels.index')])
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Create new channel.</h6>
    </div>
    <div class="card-body">

        <form id="form_data" method="POST" action="{{ route('channels.store') }}" class="ajaxForm" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" type="text" class="form-control" id="name" aria-describedby="nameHelp" placeholder="Enter channel name">
                <small id="nameHelp" class="form-text text-danger"></small>
            </div>

            <div class="form-group">
                <label for="site_id">Site</label>
                <select name="site_id" class="form-control selectpicker" id="site_id" aria-describedby="site_idHelp" title="Select site id channel">
                    @foreach($sites as $site)
                    <option value="{{ $site->id }}">{!! $site->name !!}</option>
                    @endforeach
                </select>
                <small id="site_idHelp" class="form-text text-danger"></small>
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
            <div class="clearfix">&nbsp;</div>
            <p><strong>Metadata</strong></p>
            <div class="container">
                <div class="form-group">
                <label for="title">Title</label>
                    <input name="meta[title]" type="text" class="form-control" aria-describedby="metaTitleHelp" placeholder="Title for channel" value="">
                    <small id="metaTitleHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <label for="keyword">Keyword</label>
                    <input name="meta[keyword]" type="text" class="form-control" aria-describedby="metaKeywordHelp" placeholder="Keywords that describe the channel (separated by comma)" value="">
                    <small id="metaKeywordHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <label for="description">Description</label>
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Channel description"></textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <p>Cover</p>
                    <input type="file" name="cover">
                </div>
            </div>
            <div class="clearfix">&nbsp;</div>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0"><strong>Collaboration</strong></legend>
                    <div class="col-sm-8">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="" id="collaboration-show" value="1" checked>
                            <label class="custom-control-label" for="collaboration-show"> Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="collaboration-hide" id="collaboration-hide" value="0">
                            <label class="custom-control-label" for="collaboration-hide"> No</label>
                        </div>
            <div class="clearfix">&nbsp;</div>
                <div class="form-group desc" id="Cars">
                    <input name="collaboration[collaboration-with]" type="text" class="form-control" aria-describedby="collaborationTitleHelp" placeholder="Collaboration With" value="">
                    <small id="collaborationTitleHelp" class="form-text text-danger"></small>
                </div>
                    </div>
                </div>
            </fieldset>

            <div class="clearfix">&nbsp;</div>
            <fieldset class="form-group">
                <div class="row">
                    <legend class="col-form-label col-sm-4 pt-0"><strong>Visual Interactive</strong></legend>
                    <div class="col-sm-8">
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="collaboration[VisualInteractiveShow]" id="VisualInteractive-show" value="1" checked>
                            <label class="custom-control-label" for="VisualInteractive-show"> Yes</label>
                        </div>
                        <div class="custom-control custom-radio custom-control-inline">
                            <input class="custom-control-input" type="radio" name="collaboration[VisualInteractiveShow]" id="VisualInteractive-hide" value="0">
                            <label class="custom-control-label" for="VisualInteractive-hide"> No</label>
                        </div>

                        <div id="VisualInteractive">
                            <div class="clearfix">&nbsp;</div>
                            <div class="form-group">
                                <input name="VisualInteractiveName[]" type="text" class="form-control" aria-describedby="collaborationTitleHelp" placeholder="Name Of visual interactive" value="">
                                <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                            <p><strong>Visual Interactive Cover</strong></p>
                                <input name="VisualInteractiveCover[]" type="file" aria-describedby="collaborationTitleHelp" value="">
                                <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                            </div>
                            <div class="form-group">
                            <p><strong>Visual Interactive File</strong></p>
                            <input name="collaboration[VisualInteractiveFIle]" type="hidden">
                            <input name="VisualInteractiveFile[]" type="file" aria-describedby="collaborationTitleHelp" value="">
                                <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                            </div>
                            <div id="AddVisualInteraktif"></div>
                            <div style="text-align:right">
                            <button type="button" onClick="addFunction()">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </fieldset>
            @include('components.form')
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $("#collaboration-hide").click(function() {
        $("#collaboration-show").prop('checked', false);
        $("div.desc").hide();
    });
    $("#collaboration-show").click(function() {
        $("#collaboration-hide").prop('checked', false);
        $("div.desc").show();
    });

    $("#VisualInteractive-hide").click(function() {
        $("#VisualInteractive-show").prop('checked', false);
        $("#VisualInteractive").hide();
    });
    $("#VisualInteractive-show").click(function() {
        $("#VisualInteractive-hide").prop('checked', false);
        $("#VisualInteractive").show();
    });
});

function addFunction(){
    const div = document.createElement('div');
    div.innerHTML = `
    <div id="VisualInteractive">
                                <div class="clearfix">&nbsp;</div>
                                <div class="form-group">
                                    <input name="VisualInteractiveName[]" type="text" class="form-control" aria-describedby="collaborationTitleHelp" placeholder="Name Of visual interactive" value="">
                                    <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                                </div>
                                <div class="form-group">
                                <p><strong>Visual Interactive Cover</strong></p>
                                    <input name="VisualInteractiveCover[]" type="file" aria-describedby="collaborationTitleHelp" placeholder="Collaboration With" value="">
                                    <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                                </div>
                                <div class="form-group">
                                <p><strong>Visual Interactive File</strong></p>
                                <input name="VisualInteractiveFile[]" type="file" aria-describedby="collaborationTitleHelp" value="">
                                    <small id="VisualInteractiveTitleHelp" class="form-text text-danger"></small>
                                </div>
                            </div>
      <input type="button" value="Remove" onclick="removeRow(this)" />
    `;
    
    document.getElementById('AddVisualInteraktif').appendChild(div);
}

function removeRow(input) {
document.getElementById('AddVisualInteraktif').removeChild(input.parentNode);
}

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
    });
</script>
<script src="{{ url('js/fileinput.min.js') }}"></script>
<script src="{{ url('js/fileinput-fa-theme.min.js') }}"></script>
<script src="{{ url('js/autosize.js') }}"></script>
<script src="{{ url('js/bootstrap-select.js') }}"></script>
<script src="{{ url('js/bs-custom-file-input.min.js') }}"></script>
@endpush
