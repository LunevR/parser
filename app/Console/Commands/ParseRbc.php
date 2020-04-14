<?php

namespace App\Console\Commands;

use App\Grabber\Grabber;
use Illuminate\Console\Command;

class ParseRbc extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:rbc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse articles from RBC site';

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
    public function handle()
    {
        Grabber::start('rbc');
    }
}
