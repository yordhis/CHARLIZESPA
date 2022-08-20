<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'idCustomer', 
        'idSubservice',
        'comment',
        'status',
        'point'
    ];
    
    public function customer(){
        return $this->belongsTo('App\User', 'idCustomer');
    }
    
    public function subservice(){
        return $this->belongsTo('App\Subservice', 'idSubservice');
    }
}
