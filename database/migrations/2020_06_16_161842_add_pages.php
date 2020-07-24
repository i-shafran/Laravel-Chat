<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$sql = "
			CREATE TABLE pages (
			  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
			  external_id INT UNSIGNED UNIQUE NOT NULL,
			  rule_id VARCHAR(255) UNIQUE NOT NULL,
			  rule_title VARCHAR(255) NOT NULL,
			  description TEXT,
			  mess_template TEXT,
			  risk_level VARCHAR(255) NULL DEFAULT NULL,
			  category VARCHAR(255) NULL DEFAULT NULL,
			  console TEXT,
			  cli TEXT,
			  api_check TEXT,
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
