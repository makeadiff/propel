<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCancelledCalendarEvents extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propel_cancelledCalendarEvents', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer('calendar_event_id')->unsigned();
            $table->enum('reason',array('student_not_available','volunteer_not_available'));
            $table->string('comment',500);
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
		Schema::drop('propel_cancelledCalendarEvents');
	}

}
