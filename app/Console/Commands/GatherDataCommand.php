<?php namespace App\Console\Commands;

use App\EtlConstants;
use App\Jobs\GatherRedditData;
use Illuminate\Console\Command;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Pheanstalk\Pheanstalk;

class GatherDataCommand extends Command {

    use SerializesModels;

    // The name and signature of the console command.
    protected $signature = 'data:gather';

    // The console command description.
    protected $description = 'Run a Job';

    public function __construct() {

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle() {


        $class = new GatherRedditData();
        $class->run();

    }
}
