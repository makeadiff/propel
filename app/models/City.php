<?php

class City extends Eloquent
{
    protected $table = 'City';

    public function subject()
    {
        return $this->belongsToMany('Subject','propel_city_subject')->withTimestamps();
    }

    public function wingman() {
    	return $this->hasMany("Wingman");
    }

    public function volunteer() {
    	return $this->hasMany("Volunteer");
    }

}
