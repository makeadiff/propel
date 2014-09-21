<?php

class Group extends Eloquent
{
    protected $table = 'Group';


    public function volunteer()
    {
        return $this->belongsToMany('Volunteer','UserGroup','user_id','group_id');
    }

    public function wingman()
    {
        return $this->belongsToMany('Wingman','UserGroup','user_id','group_id');
    }

    public function fellow()
    {
        return $this->belongsToMany('Fellow','UserGroup','user_id','group_id');
    }
}
