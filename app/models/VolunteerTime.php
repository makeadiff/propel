<?php

class VolunteerTime extends Eloquent
{
    protected $table = 'propel_volunteerTimes';


    public function calendarEvent()
    {
        return $this->belongsTo('CalendarEvent')->withTimestamps();
    }



}

?>
