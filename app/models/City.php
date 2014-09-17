<?php

class City extends Eloquent
{
    protected $table = 'City';

    public function subject()
    {
        return $this->belongsToMany('Subject','propel_city_subject')->withTimestamps();
    }



}

?>
