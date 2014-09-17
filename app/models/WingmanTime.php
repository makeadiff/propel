<?php

class WingmanTime extends Eloquent
{
    protected $table = 'propel_wingmanTimes';

    public function calendarEvent()
    {
        return $this->belongsTo('CalendarEvent')->withTimestamps();
    }

    public function wingmanModule()
    {
        return $this->belongsTo('WingmanModule')->withTimestamps();
    }



}

?>
