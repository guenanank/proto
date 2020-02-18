<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;

use App\Models\MongoDB\Media;
use App\Models\MongoDB\Channels;
use App\Jobs\UpdateChannels;

class ExtractSections extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:section';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data sections';

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
    public function handle(Channels $channel)
    {
        $lastId = Channels::withTrashed()->count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'section', 'skip' => $lastId, 'take' => 100, 'order' => 'created_date']
        ])->getBody();

        $channels = json_decode($client->getContents());
        $media = Media::with('group')->withTrashed()->get();

        $bar = $this->output->createProgressBar(count($channels));
        $bar->start();

        foreach ($channels as $channel) {
            $medium = $media->where('oId', $channel->site_id == 0 ? rand(1, $media->count()) : $channel->site_id)->first();
            if (empty($medium->group)) {
                continue;
            }

            $field['mediaId'] = $medium->id;
            $field['name'] = $channel->name;
            $field['slug'] = Str::slug($channel->name);
            $field['sub'] = null;
            $field['isDisplayed'] = (bool) $channel->show;
            $field['sort'] = (int) $channel->order;
            $field['analytics']['viewId'] = null;
            $field['meta']['title'] = empty($channel->title) ? null : $channel->title;
            $field['meta']['description'] = empty($channel->description) ? null : $channel->description;
            $field['meta']['keywords'] = empty($channel->keyword) ? null : $channel->keyword;
            $field['meta']['cover'] = null;
            $field['oId'] = $channel->id;
            $field['creationDate'] = Carbon::parse($channel->created_date);

            $exec = Channels::create($field);
            UpdateChannels::dispatch($exec);

            $bar->advance();
        }

        $bar->finish();
    }
}
