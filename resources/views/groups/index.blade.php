@extends('sb-admin')
@section('title', 'Groups')
@section('page_header', 'Groups')

@section('content')
<div class="float-right">
    <a href="{{ route('groups.index') }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('groups.create') }}" class="btn btn-sm btn-primary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Group</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of group</h6>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-hover table-borderless">
            <thead class="thead-default">
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Title</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Analytics ID</th>
                    <th class="text-center">Updated</th>
                    <th class="text-center">Timestamps</th>
                </tr>
            </thead>
            <tbody>
                @foreach($groups as $group)
                <tr>
                    <td>
                        <strong class="text-lg">{{ $group->name }}</strong>
                        <p>{{ $group->meta->has('title') ? $group->meta['title'] : null }}</p>
                        <div class="small">
                            <a class="text-gray-600" href="{{ route('groups.edit', ['id' => $group->id]) }}"><i class="fas fa fa-pencil-alt"></i>&nbsp;Edit</a>
                            &nbsp;|&nbsp;
                            <a class="text-gray-500 delete" href="{{ route('groups.destroy', ['id' => $group->id]) }}"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>
                        </div>
                    </td>
                    <td>{{ $group->meta['title'] }}</td>
                    <td>{{ $group->meta['description'] }}</td>
                    <td>{{ $group->analytics['gaId'] }}</td>
                    <td>{{ $group->lastUpdate }}</td>
                    <td>
                        <p>
                            <em>Last Updated {{ $group->lastUpdate->diffForHumans(['aUnit' => true]) }}</em>,<br />
                            <small>Created at {{ $group->creationDate->toDayDateTimeString() }}</small>
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
        order: [
            [3, 'desc']
        ],
        columnDefs: [{
            targets: [1, 2, 3, 4],
            visible: false
        }],
        drawCallback: function(settings) {
            $('table#dataTable thead').remove();
        },
    });
</script>
@endpush
