<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWingmanJournals extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('propel_wingmanJournals', function(Blueprint $table)
		{
			$table->increments('id');
            $table->enum('type',array('formal','informal'));
            $table->string('title',300);
            $table->string('mom',2000);
            $table->date('on_date');
            $table->integer('student_id')->unsigned();
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
		Schema::drop('propel_wingmanJournals');
	}

}
