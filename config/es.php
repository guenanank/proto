<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Elasticsearch Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the Elasticsearch connections below you wish
    | to use as your default connection for all work. Of course.
    |
    */

    'default' => env('ELASTIC_CONNECTION', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the Elasticsearch connections setup for your application.
    | Of course, examples of configuring each Elasticsearch platform.
    |
    */

    'connections' => [

        'local' => [

            'servers' => [

                [
                    "host" => env("ELASTIC_HOST", "127.0.0.1"),
                    "port" => env("ELASTIC_PORT", 9200),
                    'user' => env('ELASTIC_USER', ''),
                    'pass' => env('ELASTIC_PASS', ''),
                    'scheme' => env('ELASTIC_SCHEME', 'http'),
                ]

            ],


            'index' => env('ELASTIC_INDEX', 'articles'),

            // Elasticsearch handlers
            // 'handler' => new \Aws\ElasticsearchService\ElasticsearchPhpHandler(env('AWS_DEFAULT_REGION', 'ap-southeast-2')),
        ]
    ],

    /*
    |--------------------------------------------------------------------------
    | Elasticsearch Indices
    |--------------------------------------------------------------------------
    |
    | Here you can define your indices, with separate settings and mappings.
    | Edit settings and mappings and run 'php artisan es:index:update' to update
    | indices on elasticsearch server.
    |
    | 'my_index' is just for test. Replace it with a real index name.
    |
    */

    'indices' => [

        'channels' => [

            'aliases' => [
                'channel'
            ],

            'settings' => [
                'number_of_shards' => 1,
                'number_of_replicas' => 0,
            ],

            'mappings?include_type_name=true' => [
                'data' => [
                    'properties' => [
                        'name' => [ 'type' => 'text' ],
                        'slug' => [ 'type' => 'keyword' ],
                        'sub' => [ 'type' => 'text' ],
                        'displayed' => [ 'type' => 'boolean' ],
                        'sort' => [ 'type' => 'byte' ],
                        'ga_id' => [ 'type' => 'integer' ],
                        'meta' => [ 'type' => 'object' ],
                        'analytics' => [ 'type' => 'object' ],
                        'logs' => [ 'type' => 'nested' ],
                    ]
                ]
            ]

        ],

        'articles' => [

            'aliases' => [
                'article'
            ],

            'settings' => [
                'number_of_shards' => 3,
                'number_of_replicas' => 0,
            ],

            'mappings?include_type_name=true' => [
                'posts' => [
                    'properties' => [
                        'headline' => [ 'type' => 'object' ],
                        'slug' => [ 'type' => 'keyword' ],
                        'cover' => [ 'type' => 'object' ],
                        'published' => [ 'type' => 'date' ],
                        'channel' => [ 'type' => 'nested' ],
                        'body' => [ 'type' => 'text' ],
                        'media' => [ 'type' => 'object' ],
                        'reporter' => [ 'type' => 'object' ],
                        'editor' => [ 'type' => 'object' ],
                        'commentable' => [ 'type' => 'boolean' ],
                        'editorials' => [ 'type' => 'nested' ],
                        'relates' => [ 'type' => 'object' ],
                        'tags' => [ 'type' => 'nested' ],
                        'analytics' => [ 'type' => 'object' ],
                        'logs' => [ 'type' => 'nested' ],
                    ]
                ]
            ]
        ]

    ]

];
