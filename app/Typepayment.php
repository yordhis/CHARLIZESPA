<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Typepayment extends Model
{
    protected $fillable = [
        'name',
        'numberCount', 
        'typeCount',
        'identification',
        'entitle'
    ];
}
