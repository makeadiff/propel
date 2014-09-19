<?php

class Student extends Eloquent
{
    protected $table = 'Student';

    public function user()
    {
        return $this->belongsToMany('Wingman','propel_student_wingman')->withTimestamps();
    }

    public function calendarEvent()
    {
        return $this->hasMany('CalendarEvent');

    }

    public function wingmanJournal()
    {
        return $this->hasMany('WingmanJournal');
    }



}

?>
