<?php

class WingmanJournal extends Eloquent
{
    protected $table = 'propel_wingmanJournals';

    public function user()
    {
        return $this->belongsToMany('user','prism_reviewer_user')->withTimestamps();
    }

    public function wingman()
    {
        return $this->belongsTo('Wingman');

    }

    public function student()
    {
        return $this->belongsTo('Student');
    }



}

?>
