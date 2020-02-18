<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Channels;

class UpdateChannels implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $channel;
    private $client;
    private $uri = 'https://api.grid.id/site/old';
    private $headers = [
      'Content-Type' => 'application/json',
      'Api-Token' => '$2y$10$c1V7USh1HZSr9irAuwVcpOIRoYWhE4PCPI9jh31y4KXnoq4B3DA9C'
    ];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Channels $channel)
    {
        $this->channel = $channel;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = New Client;
        $body = $client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'section', 'id' => $this->channel->oId]
        ])->getBody();

        $channel = json_decode($body->getContents());
        $channel = reset($channel);

        $data['name'] = $channel->name;

        if ($channel->parent && $channel->parent > 0) {
            $parent = Channels::withTrashed()->where('oId', $channel->parent)->first();
        }

        $data['sub'] = !isset($parent) && empty($parent) ? null : $parent->id;
        $data['isDisplayed'] = (bool) $channel->show;
        $data['sort'] = (int) $channel->order;

        $data['meta']['title'] = empty($channel->title) ? null : $channel->title;
        $data['meta']['description'] = empty($channel->description) ? null : $channel->description;
        $data['meta']['keywords'] = empty($channel->keyword) ? null : $channel->keyword;

        if(!$channel->status) {
            $data['removedAt'] = now();
        }

        $this->channel->update($data);
        Cache::forget('channels:' . $this->channel->id);
        Cache::forget('channels:all');
        Cache::forever('channels:' . $this->channel->id, $this->channel);
    }
}
