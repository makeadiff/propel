<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWingmanTimes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propel_wingmanTimes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('calendar_event_id')->unsigned();
            $table->integer('wingman_module_id')->unsigned();
            $table->integer('wingman_id')->unsigned();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('propel_wingmanTimes');
	}

}
