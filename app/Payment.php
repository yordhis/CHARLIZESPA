<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'code',
        'titular',
        'status',
        'orde',
        'idCustomer', 
        'image',
        'date',
        'pay',
        'pendingPay',
        'payReference',
        'idTypepayment',
        'idQuote'
    ];

    public function customer(){
        $this->belongsTo('App\User', 'idCustomer');
    }
    
    // public function subservice(){
    //     $this->belongsTo('App\Subservice', 'idSubservice');
    // }

    public function typepayment(){
        $this->belongsTo('App\Typepayment', 'idTypepayment');
    }
    public function quote(){
        $this->belongsTo('App\Quote', 'idQuote');
    }
}
