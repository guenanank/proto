@extends('sb-admin')
@section('title', 'Media')
@section('page_header', 'Media')

@section('content')
<div class="float-right">
    <a href="{{ route('media.index') }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('media.create') }}" class="btn btn-sm btn-primary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Media</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of media</h6>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-hover table-borderless">
            <thead class="thead-default">
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Group Name</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Analytics ID</th>
                    <th class="text-center">Updated</th>
                    <th class="text-center">Timestamps</th>
                </tr>
            </thead>
            <tbody>
                @foreach($media as $medium)
                <tr>
                    <td>
                        <strong class="text-lg">
                            <a href="{{ $medium->domain }}" target="_blank">{{ $medium->name }}</a>
                        </strong>
                        <p>{{ isset($medium->meta['title']) ? $medium->meta['title'] : null }}</p>
                        <div class="small">
                            <a class="text-gray-500 delete" href="{{ route('media.destroy', ['id' => $medium->id]) }}"><i class="fas fa fa-trash"></i></a>
                            &nbsp;|&nbsp;
                            <a class="text-gray-600" href="{{ route('media.edit', ['id' => $medium->id]) }}"><i class="fas fa fa-pencil-alt"></i>&nbsp;Edit</a>
                        </div>
                    </td>
                    <td>{{ isset($medium->meta['title']) ? $medium->meta['title'] : null }}</td>
                    <td>{{ $medium->groupId }}</td>
                    <td>{{ isset($medium->meta['description']) ? $medium->meta['description'] : null }}</td>
                    <td>{{ isset($medium->analytics['gaId']) ? $medium->analytics['gaId'] : null }}</td>
                    <td>{{ $medium->lastUpdate }}</td>
                    <td>
                        <p>
                            <em>Last Updated {{ $medium->lastUpdate->diffForHumans(['aUnit' => true]) }}</em>,<br />
                            <small>Created at {{ $medium->creationDate->toDayDateTimeString() }}</small>
                        </p>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $('table#dataTable').DataTable({
        drawCallback: function(settings) {
            $('table#dataTable thead').remove();
        },
        order: [
            [5, 'desc']
        ],
        columnDefs: [{
            targets: [1, 2, 3, 4, 5],
            visible: false
        }]
    });
</script>
@endpush
