<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Topics;
use App\Jobs\UpdateTopics;

class ExtractTopics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:topic';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data topics';

    private $client;
    private $uri = 'https://api.grid.id/site/old';
    private $headers = [
      'Content-Type' => 'application/json',
      'Api-Token' => '$2y$10$c1V7USh1HZSr9irAuwVcpOIRoYWhE4PCPI9jh31y4KXnoq4B3DA9C'
    ];

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $lastId = Topics::withTrashed()->count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'topic', 'skip' => $lastId, 'take' => 100, 'order' => 'created_date']
        ])->getBody();

        $topics = json_decode($client->getContents());
        $media = Media::with('group')->withTrashed()->get();

        $bar = $this->output->createProgressBar(count($topics));
        $bar->start();

        foreach ($topics as $topic) {
            $medium = $media->where('oId', $topic->site_id == 0 ? rand(1, $media->count()) : $topic->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $field['mediaId'] = $medium->id;
            $field['title'] = $topic->name;
            $field['slug'] = Str::slug($topic->name);
            $field['published'] = Carbon::parse($topic->created_date);
            $field['meta']['description'] = empty($topic->description) ? null : $topic->description;
            $field['meta']['isBrandstory'] = (bool) is_null($topic->type_ads) ? false : true;
            $field['oId'] = $topic->id;
            $field['creationDate'] = Carbon::parse($topic->created_date);

            $exec = Topics::create($field);
            UpdateTopics::dispatch($exec);
            $bar->advance();
        }

        $bar->finish();
    }
}
