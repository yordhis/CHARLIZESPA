<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subservice extends Model
{
    protected $fillable = [
        'name',
        'description',
        'partialPrice',
        'price',
        'image', 
        'idService', 
        'status' 
    ];

    public function service(){
        return $this->belongsTo('App\Service', 'idService');
    }
}
