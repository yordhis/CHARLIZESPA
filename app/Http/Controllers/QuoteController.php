<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Quote;
use App\Subservice;
use App\Typepayment;
use App\User;
use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class QuoteController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $quotes = Quote::where('id','>=', 1)->orderBy('id','desc')->get();
        $quotesArray = [];

        foreach ($quotes as $quote) {
            $subservice = Subservice::findOrFail($quote->idSubservice);
            $customer = User::findOrFail($quote->idCustomer);
                if(!empty($quote->idWorker)){
                    $worker = Worker::findOrFail($quote->idWorker);
                }

            $quotesArray[] = [
                "quote" => $quote,
                "customer" => $customer,
                "subservice" => $subservice,
                "worker" => $worker ?? null
            ];
        }

        return response()->json([
            "quotes" => $quotesArray,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

   

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $quote = Quote::create($request->all());
        return response()->json([
            "message" => "Se creo correctamente",
            "quote" => $quote,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function show(Quote $quote)
    {
        $subservice = Subservice::findOrFail($quote->idSubservice);
        $customer = User::findOrFail($quote->idCustomer);
        

        return response()->json([
            "quote" => $quote,
            "subservice" => $subservice,
            "customer" => $customer,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quote $quote)
    {
        $quote->update($request->all());
        return response()->json([
            "message" => "Se creo correctamente",
            "quote" => $quote,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Quote  $quote
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quote $quote)
    {
        $quote->delete();
        return response()->json([
            "message" => "Se creo correctamente",
            "quote" => $quote,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Listar citas del cliente
     * 
     * Se espera
     * @param idCustomer 
     * 
     * @return Respuesta-JSON
     */
    public function mysquotes($idCustomer){
        $mysquotes =  Quote::where('idCustomer', $idCustomer)->orderBy('id','desc')->get();
        $mysquotesArray = [];
      
        foreach ($mysquotes as $myquote) {
            $subservice = Subservice::findOrFail($myquote->idSubservice);
            $customer = User::findOrFail($myquote->idCustomer);
            
            if(!empty($myquote->idWorker)){
                $worker = Worker::findOrFail($myquote->idWorker);
            }

            $mysquotesArray[] = [
                "myquote" => $myquote,
                "subservice" => $subservice,
                "worker" => $worker,
                "customer" => $customer
            ];
        }

        return response()->json([
            "mysquotes" => $mysquotesArray,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

}
