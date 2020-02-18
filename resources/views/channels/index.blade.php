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
<<<<<<< HEAD
            targets: [1, 2, 3],
            visible: false
        }]
=======
            targets: 0,
            render: function(data, type, row) {
                // alert(row.site.domain);
                return '<strong class="text-info"><a href="' + row.site.domain + '/' + row.slug + '">' + data + '</a></strong>&nbsp;' +
                    '<a href="' + row.site.domain + '" class="badge badge-pill badge-secondary">' + row.site.name + '</a>' +
                    '<p>' + row.meta.description.substr(0, 77) + ' ...</p>' +
                    '<div>' +
                    '<a class="text-info" href="' + baseUrl + '/channels/' + row.id + '/edit"><i class="fas fa fa-edit"></i>&nbsp;Edit</a>' +
                    '&nbsp;|&nbsp;' +
                    '<a class="text-danger delete" href="' + baseUrl + '/channels/' + row.id + '"><i class="fas fa fa-trash"></i>&nbsp;Delete</a>' +
                    '</div>';
            }
        }, {
            targets: 1,
            render: function(data, type, row) {
                return '<p>' +
                    '<em>Last updated ' + moment(row.updated_at).fromNow() + '</em><br />' +
                    '<small>Created at ' + moment(row.created_at).format('LL') + '</small>' +
                    '</p>';
            }
        }],
        createdRow: function(row, data, dataIndex) {
            if (data.displayed === false) {
                $(row).addClass('bg-gray-200');
            }
        },
        drawCallback: function(settings) {
            $('table#dataTable thead').remove();
        }
>>>>>>> 569dac0cb4ec1dc5d8827dbd15061c717814935b
    });
</script>
@endpush
