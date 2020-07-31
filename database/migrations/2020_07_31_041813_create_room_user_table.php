<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoomUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		$sql = "
			CREATE TABLE room_user (
			  room_id INT UNSIGNED NOT NULL,
			  user_id INT UNSIGNED NOT NULL,
				constraint user_id_fk
					foreign key (user_id) references users (id)
						ON UPDATE CASCADE 
						ON DELETE CASCADE,
				constraint room_id_fk
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
