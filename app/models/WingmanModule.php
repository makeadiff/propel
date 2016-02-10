<?php

class WingmanModule extends Eloquent
{
    protected $table = 'propel_wingmanModules';

    public function wingmanTime()
    {
        return $this->hasMany('WingmanTime');
    }



}

?>
