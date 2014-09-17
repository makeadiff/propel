<?php

class WingmanJournal extends Eloquent
{
    protected $table = 'propel_wingmanJournals';

    public function user()
    {
        return $this->belongsToMany('user','prism_reviewer_user')->withTimestamps();
    }



}

?>
