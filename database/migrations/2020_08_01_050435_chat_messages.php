<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChatMessages extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$sql = "
			CREATE TABLE chat_messages (
			  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
			  room_id INT UNSIGNED NOT NULL,
			  message TEXT,
			  created_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  updated_at timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
			  		constraint chat_messages_room_id_fk
					foreign key (room_id) references rooms (id)
						ON UPDATE CASCADE 
						ON DELETE CASCADE

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
