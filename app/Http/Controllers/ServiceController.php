<?php

namespace App\Http\Controllers;

use App\Service;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;

class ServiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $servicesAll = Service::all();
        $services = [];
        
        foreach ($servicesAll as $service) {
            $service['image'] = env('APP_URL').$service->image;
        }

        foreach ($servicesAll as $service) {
            if($service->status == 1){
                $services[] = $service;
            }
        }

        return response()->json([
            "services" => $services,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
    public function servicesAll()
    {
        $servicesAll = Service::all();
       
        
        foreach ($servicesAll as $service) {
            $service['image'] = env('APP_URL').$service->image;
        }

    
        return response()->json([
            "services" => $servicesAll,
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
            $img = $request->file('file')->store('public/servicios');
            $url = Storage::url($img);
            $request['image'] = $url;
        }      

        $service = Service::create($request->all());
        return response()->json([
            "message" => "Se Creo correctamente",
            "service" => $service,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        
        $service['image'] = env('APP_URL').$service->image;
        
        return response()->json([
            "service" => $service,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

   

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Service $service)
    {
        // validamos que halla imagen
        if(!empty($request->file('file'))){
            // verificamos si hay imagen actual para eliminar la
            if (!empty($service->image)) {
                $url = str_replace('storage', 'public', $service->image);
                $result = Storage::delete($url);
            }

            // subir y actualizar nombre de imagen
            $img = $request->file('file')->store('public/servicios');
    
            $url = Storage::url($img);
    
            $request['image'] = $url;
        }else {
            $request['image'] = $service->image;
        }
        
        // Se ejecuta la actualización 
        $service->update($request->all());

        return response()->json([
            "message" => "Se Actualizó correctamente",
            "service" => $service,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Service  $service
     * @return \Illuminate\Http\Response
     */
    public function destroy(Service $service)
    {
        if (!empty($service->image)) {
            $url = str_replace('storage', 'public', $service->image);
            $result = Storage::delete($url);
        }
       

        $service->delete();
        return response()->json([
            "message" => "Se Eliminó correctamente",
            "service" => $service,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
