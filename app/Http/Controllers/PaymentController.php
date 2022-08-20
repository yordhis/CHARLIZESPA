<?php

namespace App\Http\Controllers;

use App\Payment;
use App\Quote;
use App\Service;
use App\Subservice;
use App\Typepayment;
use App\User;
use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{

    /**
     * Listar citas del cliente
     * 
     * Se espera
     * @param idCustomer 
     * 
     * @return Respuesta-JSON
     */
    public function myspayments($idCustomer){
        $myspayments =  Payment::where('idCustomer', $idCustomer)->orderBy('id','desc')->get();
        $myspaymentsArray = [];
      
        foreach ($myspayments as $mypayment) {
            $quote = Quote::findOrFail($mypayment->idQuote);
            if(!empty($quote->idWorker)){
                $worker = Worker::findOrFail($quote->idWorker);
            }
            $typepayment = Typepayment::findOrFail($mypayment->idTypepayment);
            $subservice = Subservice::findOrFail($quote->idSubservice);
            $customer = User::findOrFail($quote->idCustomer);

            $myspaymentsArray[] = [
                "mypayment" => $mypayment,
                "subservice" => $subservice,
                "customer" => $customer,
                "typepayment" => $typepayment,
                "quote" => $quote,
                "worker" => $worker ?? null
            ];
        }

        return response()->json([
            "myspayments" => $myspaymentsArray,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payments = Payment::where('id', '>', 0)->orderBy('id','desc')->get();
        $paymentsArray = [];
      
        foreach ($payments as $payment) {
            $quote = Quote::findOrFail($payment->idQuote);
            if(!empty($quote->idWorker)){
                $worker = Worker::findOrFail($quote->idWorker);
            }
            $typepayment = Typepayment::findOrFail($payment->idTypepayment);
            $subservice = Subservice::findOrFail($quote->idSubservice);
            $customer = User::findOrFail($quote->idCustomer);

            $payment['image'] = env('APP_URL').$payment->image;

            $paymentsArray[] = [
                "payment" => $payment,
                "subservice" => $subservice,
                "customer" => $customer,
                "typepayment" => $typepayment,
                "quote" => $quote,
                "worker" => $worker ?? null
            ];
        }

        return response()->json([
            "payments" => $paymentsArray,
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
        if(!empty($request->file('file'))){
            $img = $request->file('file')->store('public/comprobantes');

            $url = Storage::url($img);
    
            $request['image'] = $url;
        }   

   

        $request['code'] = time();

        $payment = Payment::create($request->all());
        return response()->json([
            "message" => "Se creo correctamente",
            "payment" => $payment,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function show(Payment $payment)
    {
      
        $quote = Quote::findOrFail($payment->idQuote);
        
        if(!empty($quote->idWorker)){
            $worker = Worker::findOrFail($quote->idWorker);
        }

        $typepayment = Typepayment::findOrFail($payment->idTypepayment);
        $subservice = Subservice::findOrFail($quote->idSubservice);
        $customer = User::findOrFail($quote->idCustomer);
        
        $payment['image'] = env('APP_URL').$payment->image;

        return response()->json([
            "payment" => $payment,
            "customer" => $customer,
            "subservice" => $subservice,
            "typepayment" => $typepayment,
            "quote" => $quote,
            "worker" => $worker ?? null,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Payment $payment)
    {
        $payment->update($request->all());
        return response()->json([
            "message" => "Se actualizó correctamente",
            "payment" => $payment,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Payment  $payment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Payment $payment)
    {
        if (!empty($payment->image)) {
            $url = str_replace('storage', 'public', $payment->image);
            $result = Storage::delete($url);
        }

        $payment->delete();
        
        return response()->json([
            "message" => "Se eliminó correctamente",
            "payment" => $payment,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
