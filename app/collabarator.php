<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class collabarator extends Model
{
    protected $table="collabarator";
    
   
    public function notes(){
        return $this->belongsTo(Notes::class);
    }
}
