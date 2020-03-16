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
                    <th class="text-center">Description</th>
                    <th class="text-center">Channel</th>
                    <th class="text-center">Keywords</th>
                    <th class="text-center">Published</th>
                    <th class="text-center">Timestamps</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let pad = function(num, size) {
        var s = num + "";
        while (s.length < size) s = "0" + s;
        return s;
    }

    let slug = function(str) {
        str = str.replace(/^\s+|\s+$/g, ''); // trim
        str = str.toLowerCase();

        // remove accents, swap ñ for n, etc
        var from = "àáãäâèéëêìíïîòóöôùúüûñç·/_,:;";
        var to = "aaaaaeeeeiiiioooouuuunc------";

        for (var i = 0, l = from.length; i < l; i++) {
            str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
        }

        return str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
            .replace(/\s+/g, '-') // collapse whitespace and replace by -
            .replace(/-+/g, '-'); // collapse dashes
    }


    $('table#dataTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            type: 'post',
            url: baseUrl + '/posts/dataTable',
            data: {
                type: 'articles'
            }
        },
        columns: [{
            data: 'headlines.title',
            title: 'Article'
        }, {
            data: 'headlines.description',
            title: 'Description'
        }, {
            data: 'channel.name',
            title: 'Channel'
        }, {
            data: 'headlines.tag',
            title: 'Keywords'
        }, {
            data: 'published',
            title: 'Published On'
        }, {
            data: 'lastUpdate',
            title: 'Timestamps'
        }],
        order: [
            [4, 'desc']
        ],
        columnDefs: [{
            targets: 0,
            orderable: false,
            render: function(data, type, row) {
                let article, badgePublished, title;
                if (row.published) {
                    badgePublished = row.published > moment().format('YYYY-MM-DD HH:mm:ss') ?
                        '&nbsp;<span class="badge badge-secondary">Scheduled article</span>' :
                        '<small>Published on ' + moment(row.published).format('lll') + '</small>';

                    title = row.published > moment().format('YYYY-MM-DD HH:mm:ss') ?
                        row.headlines.title :
                        '<a href="' + row.media.domain + '/read/' + pad(row.media.oId, 2) + row.oId + '/' + slug(row.headlines.title) + '" target="_blank">' + row.headlines.title + '</a>'

                    article = '<p class="font-weight-bold">' + title + '</p>' +
                        '<div>' +
                        badgePublished +
                        '<p class="small mt5">Authored by ' + row.reporter + ', Published by ' + row.editor + '</p>' +
                        '</div>';
                } else {
                    article = row.headlines.title +
                        '<div>' +
                        '<span class="badge badge-warning">Draft</span>' +
                        '<p class="small mt5">Authored by ' + row.reporter + '</p>' +
                        '</div>';
                }
                return '<p>' + row.media.name + ' - ' + row.channel.name + '</p>' + article +
                    '<div class="small">' +
                    '<a class="text-gray-500 delete" href="' + baseUrl + '/posts/articles/' + row._id + '"><i class="fas fa fa-trash"></i></a>' +
                    '&nbsp;|&nbsp;' +
                    '<a class="text-gray-600" href="' + baseUrl + '/posts/articles/' + row._id + '/edit"><i class="fas fa fa-pencil-alt"></i>&nbsp;Edit</a>' +
                    '</div>';
            }
        }, {
            targets: 2,
            render: function(data) {
                return typeof data == 'undefined' ? null : data;
            }
        }, {
            targets: [1, 2, 3, 4],
            visible: false
        }, {
            targets: 5,
            orderable: false,
            render: function(data, type, row) {
                return '<p>' +
                    '<em>Last updated ' + moment(row.lastUpdate).fromNow() + '</em><br />' +
                    '<small>Created at ' + moment(row.creationDate).format('LL') + '</small>' +
                    '</p>';
            }
        }]
    });
</script>
@endpush
