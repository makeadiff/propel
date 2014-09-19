<?php

class CalendarEvent extends Eloquent
{
    protected $table = 'propel_calendarEvents';

    public function student()
    {
        return $this->belongsTo('Student');
    }

    public function volunteerTime()
    {
        return $this->hasOne('VolunteerTime');

    }

    public function wingmanTime()
    {
        return $this->hasOne('WingmanTime');
    }

    public function cancelledCalendarEvent()
    {
        return $this->hasOne('CancelledCalendarEvent');
    }





}

?>
