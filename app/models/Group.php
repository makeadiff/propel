<?php

class Group extends Eloquent
{
    protected $table = 'Group';


    public function volunteer()
    {
        return $this->belongsToMany('Volunteer','UserGroup','group_id','user_id');
    }
    public function wingman()
    {
        return $this->belongsToMany('Wingman','UserGroup','group_id','user_id');
    }

    public function fellow()
    {
        return $this->belongsToMany('Fellow','UserGroup','group_id','user_id');
    }

}
