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
        return $this->belongsToMany('Student','propel_student_wingman')->withTimestamps();
    }

    public function wingmanTime()
    {
        return $this->hasMany('WingmanTime');
    }

    public function wingmanJournal()
    {
        return $this->hasMany('WingmanJournal');
    }

    public function city()
    {
        return $this->belongsTo('City');
    }

    public function group()
    {
        return $this->belongsToMany('Group','UserGroup','user_id','group_id');
    }

}
