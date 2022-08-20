<?php

namespace App\Http\Controllers;

use App\Mail\ApplicantsRecieved;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Response;

class EmailController extends Controller
{
    // pass: })+=FEQ*Lz0u

    public function store()
    {   
        $data = request();
        $data['to'] = explode(',', $data['to']);
        foreach ($data['to'] as $to) {
            $emails[]= trim($to);
        }
    
        Mail::to($emails)->cc($emails)->send(new ApplicantsRecieved($data));
        return response()->json([
            "message" => "mensaje enviado", 
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    
    }
}
