
@extends('sb-admin')
@section('title', 'Galleries')
@section('page_header', 'Galleries')

@section('content')
<div class="float-right">
    <a href="#" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="#" class="btn btn-sm btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Gallery</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of gallery.</h6>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            @foreach($types as $kNavTab => $vNavTab)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : null }}" id="{{ $kNavTab }}-tab" data-toggle="tab" href="#{{ $kNavTab }}" role="tab" aria-controls="{{ $kNavTab }}" aria-selected="true">{{ $vNavTab }}</a>
            </li>
            @endforeach
        </ul>
        <div class="tab-content" id="myTabContent">
            @foreach($types as $kNavContent => $vNavContent)
            <div class="tab-pane fade {{ $loop->first ? 'show active' : null }}" id="{{ $kNavContent }}" role="tabpanel" aria-labelledby="{{ $kNavContent }}-tab">
                <div class="clearfix">&nbsp;</div>
                <div class="container">
                    <div class="card-columns">
                      @foreach ($galleries as $image)

                      <div class="card">
                        <img src="https://loremflickr.com/{{ rand(200, 350) }}/{{ rand(200, 350) }}?random={{ rand(1, 100) }}" class="card-img-top" alt="{{ $image->meta->title }}">
                        <div class="card-body">
                          <h5 class="card-title">{{ $image->meta->title }}</h5>
                          <p class="card-text">{{ $image->meta->caption }}</p>
                          <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                      </div>
                      @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
@endpush
