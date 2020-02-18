<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Hash;

use App\User;
use App\Jobs\UpdateUsers;

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
        $lastId = User::count();
        $client = $this->client->get($this->uri, [
          'headers' => $this->headers,
          'query' => ['table' => 'user', 'skip' => $lastId, 'take' => 100, 'order' => 'created_date']
        ])->getBody();

        $users = json_decode($client->getContents());
        $bar = $this->output->createProgressBar(count($users));
        $bar->start();

        foreach ($users as $user) {
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
            $field['profiles']['avatar'] = null;
            $field['profiles']['social']['facebook'] = $user->facebook;
            $field['profiles']['social']['twitter'] = $user->twitter;
            $field['profiles']['social']['instagram'] = null;
            $field['oId'] = $user->id;
            $field['creationDate'] = Carbon::parse($user->modified_date);
            
            $exec = User::create($field);
            UpdateUsers::dispatch($exec);

            $bar->advance();
        }
        $bar->finish();
    }
}
