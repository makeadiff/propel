<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCalendarEvents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propel_calendarEvents', function(Blueprint $table)
		{
			$table->increments('id');
            $table->enum('type',array('child_busy','volunteer_time','wingman_time'));
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->integer('student_id')->unsigned();
            $table->enum('status',array('created','approved','attended','cancelled'));
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
		Schema::drop('propel_calendarEvents');
	}

}
