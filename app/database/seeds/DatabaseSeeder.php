<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

        DB::table('propel_fellow_wingman')->delete();
        DB::table('propel_student_wingman')->delete();
        Subject::truncate();
        DB::table('propel_city_subject')->delete();
        CalendarEvent::truncate();
        CancelledCalendarEvent::truncate();
        WingmanModule::truncate();
        WingmanTime::truncate();
        VolunteerTime::truncate();
        WingmanJournal::truncate();

        $fellow = Fellow::find(1);

        $wingman1 = Wingman::find(2);
        $wingman2 = Wingman::find(3);

        $fellow->wingman()->attach($wingman1);
        $fellow->wingman()->attach($wingman2);


        $student1 = Student::find(3);
        $student2 = Student::find(4);

        $wingman1->student()->attach($student1);
        $wingman1->student()->attach($student2);

        $cEvent1 = new CalendarEvent;

        $cEvent1->type = 'volunteer_time';
        $cEvent1->student()->associate($student1);
        $cEvent1->status = 'created';
        $cEvent1->save();

        $vTime1 = new VolunteerTime;
        $vTime1->calendarEvent()->associate($cEvent1);


        $volunteer1 = Volunteer::find(4);
        $vTime1->volunteer()->associate($volunteer1);


        $subject1 = new Subject;
        $subject1->name = "English";
        $subject1->save();

        $vTime1->subject()->associate($subject1);

        $vTime1->save();

        $cEvent2 = new CalendarEvent;

        $cEvent2->type = 'wingman_time';
        $cEvent2->student()->associate($student1);
        $cEvent2->status = 'created';
        $cEvent2->save();

        $wTime1 = new WingmanTime;
        $wTime1->calendarEvent()->associate($cEvent2);
        $wTime1->wingman()->associate($wingman1);

        $wModule1 = new WingmanModule;
        $wModule1->name = "Programming";
        $wModule1->save();

        $wTime1->wingmanModule()->associate($wModule1);
        $wTime1->save();

        $city1 = City::find(1);
        $subject1->city()->attach($city1);

        $wJournal1 = new WingmanJournal;
        $wJournal1->type = 'formal';
        $wJournal1->title = "Day at Navy Camp";
        $wJournal1->mom = "It was awesome";
        $wJournal1->student()->associate($student1);
        $wJournal1->wingman()->associate($wingman1);
        $wJournal1->save();



	}

}
