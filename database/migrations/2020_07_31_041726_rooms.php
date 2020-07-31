<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Rooms extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$sql = "
			CREATE TABLE rooms (
			  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
			  name VARCHAR(255) NOT NULL,
			  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
			)";
		DB::statement($sql);    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
