<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

use App\Models\MongoDB\Media;
use App\User;

class ExtractUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get old data users';

    private $client;
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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Cache::forget('inUser');
        // return;
        $skip = Cache::get('inUser', 0);
        $interval = 100;
        $total = $this->client->get($this->uri . 'count', [
          'query' => ['table' => 'user']
        ])->getBody();

        $media = Cache::rememberForever('media:all', function() {
            return Media::withTrashed()->with('group')->get();
        });

        if ($skip >= (int) $total->getContents()) {
            Cache::forget('inUser');
            return;
        }

        $client = $this->client->get($this->uri . 'users', [
          'query' => ['skip' => $skip, 'take' => $interval, 'order' => 'created_date']
        ])->getBody();

        foreach (json_decode($client->getContents()) as $user) {
            $field['username'] = $user->username;
            $field['email'] = $user->email;
            $field['password'] = Hash::make('grid2020');
            $field['employeeId'] = empty($user->nik) ? null : $user->nik;
            $field['profiles']['summary'] = sprintf('%s\n%s\n%s', $user->introduction, $user->profile, $user->job_title);
            $field['profiles']['fullname'] = $user->fullname;
            $field['profiles']['gender'] = null;
            $field['profiles']['birthday'] = null;
            $field['profiles']['marital'] = null;
            $field['profiles']['phone'] = null;
            $field['profiles']['social']['facebook'] = $user->facebook;
            $field['profiles']['social']['twitter'] = $user->twitter;
            $field['profiles']['social']['instagram'] = null;
            $field['creationDate'] = Carbon::parse($user->created_date);
            if (!$user->status) {
                $field['removedAt'] = Carbon::parse($user->modified_date);
            }

            if (!empty($user->photo) && filter_var($user->photo, FILTER_VALIDATE_URL)) {
                $path = sprintf('users/avatar/');
                $filename = sprintf('%s-%s.jpeg', Str::slug($user->fullname), $user->id);
                $field['profiles']['avatar'] = $filename;

                $imgHeaders = get_headers($user->photo);
                if (strpos($imgHeaders[0], '404') !== false || strpos($imgHeaders[0], '403') !== false) {
                    $img = Image::canvas(800, 600)->text($imgHeaders[0], 120, 100);
                } else {
                    $img = @getimagesize($user->photo)
                    ? Image::make($user->photo)
                    : Image::canvas(800, 600)->text($user->fullname, 120, 100);
                }

                Storage::put($path . $filename, $img->encode('jpeg', 100), 'public');
            }

            $userModel = User::updateOrCreate(['oId' => $user->id], $field);

            Cache::forget('users:' . $userModel->id);
            Cache::forget('users:all');
            // Cache::forever('users:' . $userModel->id, $userModel);
            $this->line(is_null($userModel) ? 'empty' : sprintf('Extracted %s', $userModel->profiles['fullname']));
        }

        Cache::increment('inUser', $interval);
    }
}
