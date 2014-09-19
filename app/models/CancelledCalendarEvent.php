<?php

class CancelledCalendarEvent extends Eloquent
{
    protected $table = 'propel_cancelledCalendarEvents';

    public function calendarEvent()
    {
        return $this->belongsTo('CalendarEvent');
    }



}

?>
