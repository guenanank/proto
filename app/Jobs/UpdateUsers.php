<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

use App\User;

class UpdateUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $user;
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
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client;
        $body = $client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'user', 'id' => $this->user->oId]
        ])->getBody();

        $user = json_decode($body->getContents());
        $user = reset($user);

        $data = [
          'email' => $user->email,
          'employeeId' => empty($user->nik) ? null : $user->nik,
        ];

        if(!empty($user->photo)) {
            $path = sprintf('users/avatar/');
            $filename = sprintf('%s-%s.webp', Str::slug($user->fullname), $this->user->id);
            $data['profiles']['avatar'] = $filename;

            $imgHeaders = get_headers($user->photo);
            $img = strpos($imgHeaders[11], '404') !== false
              ? Image::canvas(800, 600)->text($imgHeaders[11], 120, 100)
              : Image::make($user->photo);

            Storage::put($path . $filename, $img->encode('webp'), 'public');
        }

        if(!$user->status) {
            $data['removedAt'] = now();
        }

        $this->user->update($data);
        Cache::forget('users:' . $this->user->id);
        Cache::forget('users:all');
        Cache::forever('users:' . $this->user->id, $this->user);
    }
}
