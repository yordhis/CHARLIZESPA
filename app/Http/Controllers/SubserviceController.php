<?php

namespace App\Http\Controllers;

use App\Service;
use App\Subservice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class SubserviceController extends Controller
{
    /**
     * Despliega una lista del recurso 
     * Filtrado por el ID del servicio
     * 
     * @return \Illuminate\Http\Response
     */
    public function filter($idService)
    {
       
        $subservicesFilterAll =  Subservice::where('idService', $idService)->get();
        $subservicesFilter = [];

        foreach ($subservicesFilterAll as $subservicesFilt) {
            $subservicesFilt['image'] = env('APP_URL').$subservicesFilt->image;
        }
        
        foreach ($subservicesFilterAll as $subservicesFilt) {
            if($subservicesFilt->status == 1){
                $subservicesFilter[] = $subservicesFilt;
            }
        }

        return response()->json([
            "subservicesFilter" => $subservicesFilter,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }



    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function subservicesAll()
    {
        $subservicesAll = Subservice::all();
       
        foreach ($subservicesAll as $subservice) {
            $subservice['image'] = env('APP_URL').$subservice->image;
        }
      
        return response()->json([
            "subservices" => $subservicesAll,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    public function index()
    {
        $subservicesAll = Subservice::all();
        $subservices = [];
        foreach ($subservicesAll as $subservice) {
            $subservice['image'] = env('APP_URL').$subservice->image;
        }

        foreach ($subservicesAll as $subservice) {
            if($subservice->status == 1){
                $subservices[] = $subservice;
            }
        }

      
        return response()->json([
            "subservices" => $subservices,
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
            $img = $request->file('file')->store('public/subservicios');
            $url = Storage::url($img);
            $request['image'] = $url;
        }   
        
        
        $subservice = Subservice::create($request->all());
        return response()->json([
            "message" => "Se creo correctamente",
            "subservice" => $subservice,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Subservice  $subservice
     * @return \Illuminate\Http\Response
     */
    public function show(Subservice $subservice)
    {

        $subservice['image'] = env('APP_URL').$subservice->image;

        return response()->json([
            "subservice" => $subservice,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Subservice  $subservice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Subservice $subservice)
    {
         // validamos que halla imagen
         if(!empty($request->file('file'))){
            // verificamos si hay imagen actual para eliminar la
            if (!empty($subservice->image)) {
                $url = str_replace('storage', 'public', $subservice->image);
                $result = Storage::delete($url);
            }

            // subir y actualizar nombre de imagen
            $img = $request->file('file')->store('public/subservicios');
    
            $url = Storage::url($img);
    
            $request['image'] = $url;
        }else {
            $request['image'] = $subservice->image;
        }

        $subservice->update($request->all());

        return response()->json([
            "message" => "Se actualizó correctamente",
            "subservice" => $subservice,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Subservice  $subservice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subservice $subservice)
    {

        if (!empty($subservice->image)) {
            $url = str_replace('storage', 'public', $subservice->image);
            $result = Storage::delete($url);
        }

        $subservice->delete();

        return response()->json([
            "message" => "Se eliminó correctamente",
            "subservice" => $subservice,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
