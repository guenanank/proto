@extends('sb-admin')
@section('title', 'Visual Interactive')
@section('page_header', 'Visual Interactive')

@push('styles')
<style>
    .card-img: {
      opacity: 0.5;
    }
</style>
@endpush

@section('content')
<div class="float-right">
    <a href="#" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('visualinteractives.create') }}" class="btn btn-sm btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Visual Interaktif</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of Visual Interaktif.</h6>
    </div>
    <div class="card-body">

        <form class="form-inline float-right">
            <label class="my-1 mr-2" for="inline-search">Search:</label>
            <input type="text" class="form-control form-control-sm" id="inline-search" />
        </form>
        <div class="clearfix">&nbsp;</div>
        <div class="clearfix">&nbsp;</div>

        <div class="card-columns">
        @foreach ($visual_interactive as $image)
            <div class="card infinite">
                <img src="{{ $image->cover->image->path_image }}" class="card-img-top" alt="{{ $image->meta->title }}">
                <div class="card-body">
                    <h5 class="card-title">{{ $image->meta->title }} </h5>
                    <p class="card-text"><small class="text-muted">Last updated {{ $image->updated_at->diffForHumans(['aUnit' => true]) }}</small></p>
                    <div>
                        <a class="text-info" href="{{ route('visualinteractives.edit', ['id' => $image->id]) }}"><i class="fas fa fa-edit"></i>&nbsp;Edit</a>
                        &nbsp;|&nbsp;
                        <a class="text-danger delete" href="{{ route('visualinteractives.destroy', ['id' => $image->id]) }}"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>
                    </div>
                </div>
            </div>
        @endforeach
        </div>
        <!-- status elements -->
        <div class="clearfix">&nbsp;</div>
        <div class="scroller-status">
            <div class="infinite-scroll-request loader-ellips">
                ...
            </div>
            <p class="infinite-scroll-last">End of content</p>
            <p class="infinite-scroll-error">No more pages to load</p>
        </div>
        <div class="clearfix">&nbsp;</div>
        
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ url('js/infinite-scroll.pkgd.min.js') }}"></script>
<script>
    $('.card-columns').infiniteScroll({
        path: '.page-link[rel="next"]',
        append: '.infinite',
        status: '.scroller-status',
        hideNav: '.pagination',
    });
</script>
@endpush
