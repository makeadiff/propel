<?php

class Wingman extends Eloquent
{
    protected $table = 'User';

    public function fellow()
    {
        return $this->belongsToMany('Fellow','propel_fellow_wingman')->withTimestamps();
    }

    public function student()
    {
        return $this->hasMany('Student','propel_student_wingman')->withTimestamps();
    }



}

?>
