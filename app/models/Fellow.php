<?php

class Fellow extends Eloquent
{
    protected $table = 'User';

    public function wingman()
    {
        return $this->belongsToMany('Wingman','propel_fellow_wingman')->withTimestamps();
    }

    public function group()
    {
        return $this->belongsToMany('Group','UserGroup','user_id','group_id','year');
    }

    public function city()
    {
        return $this->belongsTo('City');
    }



}

?>
