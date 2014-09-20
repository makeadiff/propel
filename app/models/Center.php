<?php

class Center extends Eloquent
{
    protected $table = 'Center';

    public function city()
    {
        return $this->belongsTo('City');
    }

    public function student() 
    {
    	return $this->hasMany("Student");
    }

}
