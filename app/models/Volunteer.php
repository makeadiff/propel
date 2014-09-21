<?php

class Volunteer extends Eloquent
{
    protected $table = 'User';


    public function volunteerTime()
    {
        return $this->hasMany('VolunteerTime');
    }

    public function city()
    {
        return $this->belongsTo('City');
    }
}
