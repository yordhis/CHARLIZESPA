<?php

namespace App\Http\Controllers;

use App\Typepayment;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TypepaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $typepayments = Typepayment::all();
        return response()->json([
            "typepayments" => $typepayments,
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
        $typepayment = Typepayment::create($request->all());
        return response()->json([
            "message" => "Se creo correctamente",
            "typepayment" => $typepayment,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Typepayment  $typepayment
     * @return \Illuminate\Http\Response
     */
    public function show(Typepayment $typepayment)
    {
        return response()->json([
            "typepayment" => $typepayment,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Typepayment  $typepayment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Typepayment $typepayment)
    {
        $typepayment->update($request->all());
        return response()->json([
            "message" => "Se actualizó correctamente",
            "typepayment" => $typepayment,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Typepayment  $typepayment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Typepayment $typepayment)
    {
        $typepayment->delete();
        return response()->json([
            "message" => "Se eliminó correctamente",
            "typepayment" => $typepayment,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
