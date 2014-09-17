<?php

class Fellow extends Eloquent
{
    protected $table = 'User';

    public function wingman()
    {
        return $this->belongsToMany('Wingman','propel_fellow_wingman')->withTimestamps();
    }



}

?>
