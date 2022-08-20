<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    protected $fillable = [
        'idSubservice',
        'idCustomer',
        'idWorker',
        'time',
        'duration',
        'date',
        'status'
    ];

    public function subservice(){
        return $this->belongsTo('App\Subservice', 'idSubservice');
    }
    
    public function customer(){
        return $this->belongsTo('App\User', 'idCustomer');
    }
    // public function payment(){
    //     return $this->belongsTo('App\Payment', 'idPayment');
    // }

}
