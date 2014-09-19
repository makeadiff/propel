<?php

class WingmanTime extends Eloquent
{
    protected $table = 'propel_wingmanTimes';

    public function calendarEvent()
    {
        return $this->belongsTo('CalendarEvent');
    }

    public function wingmanModule()
    {
        return $this->belongsTo('WingmanModule');
    }

    public function wingman()
    {
        return $this->belongsTo('Wingman');
    }



}

?>
