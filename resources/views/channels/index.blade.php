@extends('sb-admin')
@section('title', 'Channels')
@section('page_header', 'Channels')

@section('content')
<div class="float-right">
    <a href="{{ route('channels.index') }}" class="btn btn-sm btn-light btn-icon-split">
        <span class="icon text-white-50">
            <i class="fa fa-sync"></i>
        </span>
        <span class="text">Refresh</span>
    </a>
    &nbsp;
    <a href="{{ route('channels.create') }}" class="btn btn-sm btn-primary btn-icon-split">
        <span class="icon text-white-50">
            <i class="fas fa-plus-circle"></i>
        </span>
        <span class="text">New Channel</span>
    </a>
</div>
<div class="clearfix">&nbsp;</div>
<div class="clearfix">&nbsp;</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Master data of channel.</h6>
    </div>
    <div class="card-body">
        <table id="dataTable" class="table table-hover table-borderless">
            <caption class="small">*Highlighted lines are channels that are not displayed on the web.</caption>
            <thead class="thead-default">
                <tr>
                    <th class="text-center">Name</th>
                    <th class="text-center">Media</th>
                    <th class="text-center">Description</th>
                    <th class="text-center">Keywords</th>
                    <th class="text-center">Timestamps</th>
                </tr>
            </thead>
            <tbody>
            @foreach($channels as $channel)
              @continue(empty($channel->media))
              <tr class="{{ $channel->isDisplayed ? '' : 'bg-gray-200' }}">
                  <td>
                      <strong class="text-lg">
                        <a href="{{ $channel->media->domain . '/' . $channel->slug }}" target="_blank">{{ $channel->name }}</a>
                      </strong>
                      <p>{{ $channel->meta['title'] }}</p>
                      <div class="small">
                          <a class="text-gray-600" href="{{ route('channels.edit', ['id' => $channel->id]) }}"><i class="fas fa fa-pencil-alt"></i>&nbsp;Edit</a>
                          &nbsp;|&nbsp;
                          <a class="text-gray-500 delete" href="{{ route('channels.destroy', ['id' => $channel->id]) }}"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>
                      </div>
                  </td>
                  <td>{{ $channel->media->name }}</td>
                  <td>{{ $channel->meta['description'] }}</td>
                  <td>{{ $channel->meta['keywords'] }}</td>
                  <td>
                      <p>
                          <em>Last Updated {{ $channel->lastUpdate->diffForHumans(['aUnit' => true]) }}</em>,<br />
                          <small>Created at {{ $channel->creationDate->toDayDateTimeString() }}</small>
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
            [3, 'desc']
        ],
        columnDefs: [{
            targets: [1, 2, 3],
            visible: false
        }]
    });
</script>
@endpush
