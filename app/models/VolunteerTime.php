<?php

class VolunteerTime extends Eloquent
{
    protected $table = 'propel_volunteerTimes';


    public function calendarEvent()
    {
        return $this->belongsTo('CalendarEvent');
    }

    public function volunteer()
    {
        return $this->belongsTo('Volunteer');
    }

    public function subject()
    {
        return $this->belongsTo('Subject');
    }



}

?>
