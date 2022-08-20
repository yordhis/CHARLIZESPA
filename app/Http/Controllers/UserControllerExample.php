<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    public function login(){
        $data = [
            'email' => request('email'),
            'password' => request('password')
        ];
        
        // El metodo para ejecutar la autenticacion es: 
        if(Auth::attempt($data)){
            // Solicitamos los datos del usuario
            $user = Auth::user();
            // $dataSession = session()->all();
           
            $loginData['token'] = $user->createToken('Sharlispat')->accessToken;
            return response()->json([
                "message" => "Bienvenido",
                "data" => $loginData,
                "user" => $user
                // "dataSession" => $dataSession,
            ], Response::HTTP_OK);

        }else {
            return response()->json([
                "message" => "Usuario o Contraseña invalidos",
                "status" => Response::HTTP_UNAUTHORIZED
            ], Response::HTTP_UNAUTHORIZED);
        }
    }

    // public function deleteSession($idUser){
    //     session_start();
    //     $_SESSION['']
    // }

    public function deletesession(Request $request)
    {
        // $value = $request->session()->get($id);
        // $value = $request->session()->get('key', 'default');
        // $data = session()->all();
        $message = false;
        if(session()->forget($request->token)){
            $message = true;
        }
        return response()->json([
            "message" => $message,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
        //
    }
    /**
     * register user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function register(Request $request)
    {
        $data = $request->all();
        // encriptamos la contraseña por buena practica
        $data['password'] = bcrypt($data['password']);

        // Procedemos a crear el usuario
        $user = User::create($data);

        if (!$user) {
             return response()->json([
                    "message" => "Error en el registro de usuario",
                    "status" => Response::HTTP_CONFLICT,
                    
                ], Response::HTTP_CONFLICT);
        } else {
            //Generamos el token de frente para evitar mandar al user a logearse
            $loginData['token'] = $user->createToken('Sharlispat')->accessToken;
                return response()->json([
                    "message" => "Bienvenido NUEVO USUARIO",
                    "data" => $loginData
                ], Response::HTTP_OK);
        }
        



    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::all();
        return response()->json([
            "users" => $users,
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
       
        $user = User::create($request->all());
        return response()->json([
            "message" => "Se Creo correctamente",
            "user" => $user,
            "status" => Response::HTTP_CREATED
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        
        return response()->json([
            "users" => $user,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user->update($request->all());
        return response()->json([
            "message" => "Se actualizó correctamente",
            "user" => $user,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            "message" => "Se eliminó correctamente",
            "user" => $user,
            "status" => Response::HTTP_OK
        ], Response::HTTP_OK);
    }
}
