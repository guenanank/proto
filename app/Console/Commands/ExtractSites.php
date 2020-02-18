<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(Media $medium)
    {
        // $medium->all();
        $this->info($medium->all()->toJson());
    }
}
