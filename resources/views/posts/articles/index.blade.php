@extends('sb-admin')
@section('title', 'Post Articles')
@section('page_header', 'Post Articles')

@push('styles')
@endpush

@section('content')
<div class="float-right">
    <a href="{{ route('posts', ['type' => 'articles']) }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('posts.create', ['type' => 'articles']) }}" class="btn btn-sm btn-primary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Article</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of article</h6>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-hover table-borderless">
            <thead class="thead-default">
                <tr>
                    <th class="text-center">Title</th>
                    <th class="text-center">Media</th>
                    <th class="text-center">Published</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('table#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'post',
            url: baseUrl + '/posts/dataTable',
            data: { type: 'articles' }
        },
        columns: [{
            name: 'headlines.title',
            title: 'headlines.title'
        }, {
            name: 'media.name',
            data: 'media.name'
        }, {
            name: 'published',
            data: 'published'
        }],
        order: [
            [2, 'desc']
        ],
        columnDefs: [{
          targets: 0,
          render: function(data, type, row) {
            return row.headlines.title;
          }
        }],
        drawCallback: function(settings) {
            $('table#dataTable thead').remove();
        }
    });
</script>
@endpush
