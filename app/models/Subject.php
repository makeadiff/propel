<?php

class Subject extends Eloquent
{
    protected $table = 'propel_subjects';

    public function city()
    {
        return $this->belongsToMany('City','propel_city_subject')->withTimestamps();
    }



}

?>
