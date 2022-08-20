<?php

namespace App\Http\Controllers;

use App\Worker;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class WorkerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $workers = Worker::all();

        foreach ($workers as $worker) {
            $worker['image'] = env('APP_URL').$worker->image;
        }

        return response()->json([
            "workers" => $workers,
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
        if(empty($request->file('file'))){
            return response()->json([
                "message" => "Por favor ingrese una imagen",
                "status" => Response::HTTP_CONFLICT
            ], Response::HTTP_CONFLICT);
        }   

        $img = $request->file('file')->store('public/trabajadores');

        $url = Storage::url($img);

        $request['image'] = $url;


        $worker = Worker::create($request->all());
        return response()->json([
            "message" => "Se Creo correctamente",
            "worker" => $worker,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function show(Worker $worker)
    {
        $worker['image'] = env('APP_URL').$worker->image;
        
        return response()->json([
            "worker" => $worker,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Worker $worker)
    {
         // validamos que halla imagen
         if(!empty($request->file('file'))){
            // verificamos si hay imagen actual para eliminar la
            if (!empty($worker->image)) {
                $url = str_replace('storage', 'public', $worker->image);
                $result = Storage::delete($url);
            }

            // subir y actualizar nombre de imagen
            $img = $request->file('file')->store('public/trabajadores');
    
            $url = Storage::url($img);
    
            $request['image'] = $url;
        }else {
            $request['image'] = $worker->image;
        }

        $worker->update($request->all());

        return response()->json([
            "message" => "Se Actualizó correctamente",
            "worker" => $worker,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Worker  $worker
     * @return \Illuminate\Http\Response
     */
    public function destroy(Worker $worker)
    {
        if (!empty($worker->image)) {
            $url = str_replace('storage', 'public', $worker->image);
            $result = Storage::delete($url);
        }

        $worker->delete();

        return response()->json([
            "message" => "Se Eliminó correctamente",
            "worker" => $worker,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
