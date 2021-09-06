<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Notes extends Model
{
    protected $fillable=['title','body'];
    //protected $casts = ['collabMail' => 'array'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
