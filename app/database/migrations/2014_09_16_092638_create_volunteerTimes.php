<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVolunteerTimes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propel_volunteerTimes', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('calendar_event_id')->unsigned();
            $table->integer('subject_id')->unsigned();
            $table->integer('volunteer_id')->unsigned();
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
		Schema::drop('propel_volunteerTimes');
	}

}
