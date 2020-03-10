<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

use App\Models\MongoDB\Media;

class ExtractSites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:site';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data sites';

    private $client;
    private $networks;
    private $uri = 'https://api.gridtechno.com/extract/';
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
        $this->client = new Client(['headers' => $this->headers]);
        $this->networks = [
          'Gridoto' => '5e6615c6fdce276d835f2c42',
          'Motorplus' => '5e661610fdce276d835f2c43',
          'Gridnetwork' => '5e661558fdce276aa05be152',
          'sonora' => '5e6619dcfdce276d835f2c44',
          'Bolasport' => '5e6615eefdce276aa05be153'
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // return Cache::forget('inSite');
        $skip = Cache::get('inSite', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => ['table' => 'site']
        ])->getBody();

        if ($skip >= (int) $total->getContents()) {
            return Cache::forget('inSite');
        }

        $client = $this->client->get($this->uri . 'sites', [
          'query' => ['skip' => $skip, 'take' => $interval, 'order' => 'created_date']
        ])->getBody();

        foreach (json_decode($client->getContents()) as $site) {
            if (empty($this->networks[$site->network])) {
                continue;
            }

            $field = [
              'groupId' => $this->networks[$site->network],
              'name' => $site->name,
              'domain' => $site->url,
              'analytics' => [
                'viewId' => $site->ga_view_id,
                'youtubeChannel' => $site->youtube_channel
              ],
              'meta' => [
                'title' => $site->title,
                'keywords' => $site->keyword,
                'description' => $site->description,
                'color' => null
              ],
              'assets' => [],
              'masthead' => [],
              'creationDate' => Carbon::parse($site->created_date)
            ];

            if (!$site->status) {
                $field['removedAt'] = now();
            }

            // $siteModel = Media::create($field);
            $siteModel = Media::withTrashed()->updateOrCreate(['oId', $site->id], $field);
            $this->line(is_null($siteModel) ? 'empty' : sprintf('Extracted %s', $siteModel->name));
        }

        Cache::increment('inSite', $interval);
    }
}
