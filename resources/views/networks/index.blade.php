@extends('sb-admin')
@section('title', 'Networks')
@section('page_header', 'Networks')

@section('content')
<div class="float-right">
    <a href="{{ route('networks.index') }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('networks.create') }}" class="btn btn-sm btn-success btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Network</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of network.</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="dataTable" class="table table-sm table-hover table-sm table-borderless">
                <thead class="thead-default">
                    <tr>
                        <th class="text-center">Name</th>
                        <th class="text-center">Timestamps</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($networks as $network)
                    <tr>
                        <td>
                            <strong class="text-lg">{{ $network->name }}</strong>
                            <div>
                                <a class="text-info" href="{{ route('networks.edit', ['id' => $network->id]) }}"><i class="fas fa fa-edit"></i>&nbsp;Edit</a>
                                &nbsp;|&nbsp;
                                <a class="text-danger delete" href="{{ route('networks.destroy', ['id' => $network->id]) }}"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>
                            </div>
                        </td>
                        <td>
                            <p>
                                <em>Last Updated {{ $network->updated_at->diffForHumans(['aUnit' => true]) }}</em>,<br />
                                <small>Created at {{ $network->created_at->toDayDateTimeString() }}</small>
                            </p>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
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
            [1, 'desc']
        ],
    });
</script>
@endpush
