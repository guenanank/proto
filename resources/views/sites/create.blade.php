@extends('sb-admin')
@section('title', 'Sites')
@section('page_header', 'Sites')

@push('styles')
<link href="{{ url('css/bootstrap-select.css') }}" rel="stylesheet" />
<link href="{{ url('css/bootstrap-colorpicker.css') }}" rel="stylesheet" />
<link href="{{ url('css/froala_editor.pkgd.min.css') }}" rel="stylesheet" />
<link href="{{ url('css/fileinput.css') }}" rel="stylesheet">
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

            <div class="form-group">
                <label for="networks">Networks</label>
                <select class="form-control" id="exampleFormControlSelect1" name="network_id">
                  @foreach ($networks as $key=>$value)
                    <option value="{{ $key }}">{{ $value }}</option>
                  @endforeach 
                </select>
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
                    <input name="meta[keywords]" type="text" class="form-control" aria-describedby="metaKeywordHelp" placeholder="Keywords that describe the site (separated by comma)">
                    <small id="metaKeywordHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <input name="meta[color]" type="text" class="form-control" aria-describedby="metaColorHelp" placeholder="Color for site" id="MetaColor">
                    <small id="metaColorHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                    <textarea name="meta[description]" class="form-control autosize" aria-describedby="metaDescriptionHelp" placeholder="Site description"></textarea>
                    <small id="metaDescriptionHelp" class="form-text text-danger"></small>
                </div>
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-image-tab" data-toggle="tab" href="#nav-image" role="tab" aria-controls="nav-image" aria-selected="true">Image</a>
                    <a class="nav-item nav-link" id="nav-css-tab" data-toggle="tab" href="#nav-css" role="tab" aria-controls="nav-css" aria-selected="false">CSS</a>
                    <a class="nav-item nav-link" id="nav-js-tab" data-toggle="tab" href="#nav-js" role="tab" aria-controls="nav-js" aria-selected="false">JS</a>
                  </div>
                </nav>

                <div class="tab-content" id="nav-tabContent">
                  <div class="tab-pane fade show active" id="nav-image" role="tabpanel" aria-labelledby="nav-image-tab">
                    <br/>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="hidden" name="meta[logo]" />
                            <input type="file" name="logo" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Chose main Logo</label>
                            <small id="logoHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="hidden" name="meta[logo_menu]" />
                            <input type="file" name="logo_menu" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Chose menu Logo</label>
                            <small id="logoHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="hidden" name="meta[logo_white]" />
                            <input type="file" name="logo_white" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Chose white Logo</label>
                            <small id="logoHelp" class="form-text text-danger"></small>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="custom-file">
                            <input type="hidden" name="meta[shortcut_icon]" />
                            <input type="file" name="shortcut_icon" class="custom-file-input" id="customFile">
                            <label class="custom-file-label" for="customFile">Chose shortcut Icon</label>
                            <small id="shortcut_iconHelp" class="form-text text-danger"></small>
                        </div>
                    </div> 
                  </div>

                  <div class="tab-pane fade" id="nav-css" role="tabpanel" aria-labelledby="nav-css-tab">
                    <br />
                    <div class="form-group">
                        <input type="hidden" name="meta[css]" />
                        <input class="form-control-file fileinput" name="css[]" multiple type="file" id="input-css" data-msg-placeholder="Chose file css"/>
                    </div>
                  </div>

                  <div class="tab-pane fade" id="nav-js" role="tabpanel" aria-labelledby="nav-js-tab">
                    <br />
                    <div class="form-group">
                        <input type="hidden" name="meta[js]" />
                        <input class="form-control-file fileinput" name="js[]" multiple type="file" id="input-js" data-msg-placeholder="Chose file js"/>
                    </div>
                  </div>
                </div>
            </div>


            <div class="clearfix"></div>
            <div class="container">
                <hr />
                <p>Footer</p>
                <hr />
                <nav>
                  <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-aboutUs-tab" data-toggle="tab" href="#nav-aboutUs" role="tab" aria-controls="nav-aboutUs" aria-selected="true">About Us</a>
                    <a class="nav-item nav-link" id="nav-editorial-tab" data-toggle="tab" href="#nav-editorial" role="tab" aria-controls="nav-editorial" aria-selected="false">Editorial</a>
                    <a class="nav-item nav-link" id="nav-management-tab" data-toggle="tab" href="#nav-management" role="tab" aria-controls="nav-management" aria-selected="false">Management</a>
                  </div>
                </nav>
                <div class="form-group">
                <label for="about_us">About Us</label>
                    <textarea name="footer[about_us]" class="form-control autosize" id="about_us" aria-describedby="aboutusHelp" placeholder="Enter about us site"></textarea>
                    <small id="domainHelp" class="form-text text-danger"></small>
                </div>
               <div class="form-group">
                <label for="editorial">Editorial</label>
                    <textarea name="footer[editorial]" class="form-control autosize" id="editorial" aria-describedby="aboutusHelp" placeholder="Enter about us site"></textarea>
                    <small id="domainHelp" class="form-text text-danger"></small>
                </div>
                <div class="form-group">
                <label for="management">Management</label>
                    <textarea name="footer[management]" class="form-control autosize" id="management" aria-describedby="aboutusHelp" placeholder="Enter about us site"></textarea>
                    <small id="domainHelp" class="form-text text-danger"></small>
                </div>
            </div>


            @include('components.form')
        </form>

    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/autosize.js') }}"></script>
<script src="{{ url('js/froala_editor.pkgd.min.js') }}"></script>
<script src="{{ url('js/bootstrap-select.js') }}"></script>
<script src="{{ url('js/bootstrap-colorpicker.js') }}"></script>
<script src="{{ url('js/fileinput.min.js') }}"></script>
<script src="{{ url('js/fileinput-fa-theme.min.js') }}"></script>
<script>
    $(function () {
      // Basic instantiation:
      $('#MetaColor').colorpicker();
      
      // Example using an event, to change the color of the .jumbotron background:
      $('#MetaColor').on('colorpickerChange', function(event) {
        $('.jumbotron').css('background-color', event.color.toString());
      });
    });
</script> 
<script>
// Add the following code if you want the name of the file appear on select
$(".custom-file-input").on("change", function() {
  var fileName = $(this).val().split("\\").pop();
  $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
});
</script>
<script>
  new FroalaEditor('textarea#about_us');
  new FroalaEditor('textarea#editorial');
  new FroalaEditor('textarea#management');
</script> 

<script>
    $('#input-css').fileinput({
        theme: 'fas',
        browseLabel: 'Find',
        browseIcon: '<i class=\"fa fa-search\"></i>',
        previewClass: 'bg-gray-100',
        showRemove: false,
        showUpload: false,
        maxFileCount: 5,
        maxFileSize: 100,
        allowedFileExtensions: ["css"],
    });
</script>
<script>
    $('#input-js').fileinput({
        theme: 'fas',
        browseLabel: 'Find',
        browseIcon: '<i class=\"fa fa-search\"></i>',
        previewClass: 'bg-gray-100',
        showRemove: false,
        showUpload: false,
        maxFileCount: 5,
        maxFileSize: 100,
        allowedFileExtensions: ["js"]
    });
</script>
@endpush
