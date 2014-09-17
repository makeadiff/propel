<?php

class CalendarEvent extends Eloquent
{
    protected $table = 'propel_calendarEvents';

    public function student()
    {
        return $this->belongsTo('Student')->withTimestamps();
    }

    public function volunteerTime()
    {
        return $this->hasOne('VolunteerTime')->withTimestamps();

    }

    public function wingmanTime()
    {
        return $this->hasOne('WingmanTime')->withTimestamps();
    }

    public function cancelledCalendarEvent()
    {
        return $this->hasOne('CancelledCalendarEvent')->withTimestamps();
    }





}

?>
