<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateWingmanJournalsEnum extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement("ALTER TABLE propel_wingmanJournals CHANGE type type ENUM('formal','informal','child_feedback','module_feedback')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::statement("ALTER TABLE propel_wingmanJournals CHANGE type type ENUM('formal','informal')");
	}

}
