@extends('sb-admin')
@section('title', 'Topics')
@section('page_header', 'Topics')

@section('content')
<div class="float-right">
    <a href="{{ route('topics.index') }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('topics.create') }}" class="btn btn-sm btn-primary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Topic</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of topic</h6>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-hover table-borderless">
            <thead class="thead-default">
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Media Name</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Timestamps</th>
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
            url: baseUrl + '/topics/dataTable'
        },
        columns: [{
            name: 'title',
            data: 'title'
        }, {
            name: 'media.name',
            data: 'media.name'
        }, {
            name: 'meta.description',
            data: 'meta.description'
        }, {
            name: 'lastUpdate',
            data: 'lastUpdate',
            className: 'text-right'
        }],
        order: [
            [3, 'desc']
        ],
        columnDefs: [{
            targets: 0,
            render: function(data, type, row) {
                return '<a href="' + row.media.domain + '/topic/' + row.slug + '" target="_blank"><strong>' + data + '</strong></a>&nbsp;' +
                    '<a href="' + row.media.domain + '" class="badge badge-pill badge-secondary" target="_blank">' + row.media.name + '</a>' +
                    '<p>' + row.meta.description + '<br /><small>Published at ' + moment(row.published).format('LL') + '</small></p>' +
                    '<div class="small">' +
                    '<a class="text-gray-600" href="' + baseUrl + '/topics/' + row._id + '/edit"><i class="fas fa fa-pencil-alt"></i>&nbsp;Edit</a>' +
                    '&nbsp;|&nbsp;' +
                    '<a class="text-gray-500 delete" href="' + baseUrl + '/topics/' + row._id + '"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>' +
                    '</div>';
            }
        }, {
            targets: [1, 2],
            visible: false,
        }, {
            targets: 3,
            render: function(data, type, row) {
                return '<p>' +
                    '<em>Last updated ' + moment(row.lastUpdate).fromNow() + '</em><br />' +
                    '<small>Created at ' + moment(row.creationDate).format('LL') + '</small>' +
                    '</p>';
            }
        }],
        drawCallback: function(settings) {
            $('table#dataTable thead').remove();
        },
    });
</script>
@endpush
