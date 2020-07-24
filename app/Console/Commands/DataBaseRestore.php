<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DataBaseRestore extends Command
{
    protected $signature = 'db:restore';
    protected $description = 'DataBase restore from database/last.sql';

    public function handle()
    {
        $pass_if_pass =  config('database.connections.mysql.password') ?
            ' -p'.config('database.connections.mysql.password').' ' : ' ';
        
        exec("mysql -u".config('database.connections.mysql.username').$pass_if_pass.
            config('database.connections.mysql.database')." < database/last.sql");
    }
}
