<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateRollback extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'migrate:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'One migration rollback';

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
		$last = \DB::table("migrations")->orderBy("id", "DESC")->limit(1);
		$name = $last->first()->migration;
		$last->delete();
    	$this->info("Migration $name rollback complete");
    }
}
