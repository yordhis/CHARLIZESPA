<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Token;
use Symfony\Component\VarDumper\Cloner\Data;

class UserController extends Controller
{

    public function login($email = null, $pass = null, $key_google = null){
        $data = [
            'email' => $email ?? request('email'),
            'key_google' => $key_google ?? request('key_google'),
            'password' => $pass ?? request('password')
        ];
        
        //obtenemos los datos del correo google
        $user = User::where("email", $data['email'])->get();
        
            // validamos si el correo existe     
            if (empty($user[0])) {
                return response()->json([
                    "message" => "Usuario o Email no existe",
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
              
        // validamos si el password no es null
        if(!empty($data['password'])){

            // El metodo para ejecutar la autenticacion es: 
            if(Auth::attempt($data)){
                // Solicitamos los datos del usuario
                $user = Auth::user();
                // $dataSession = session()->all();
                $loginData['token'] = $user->createToken("Sharlispat")->accessToken;
            
                // asignamos el token al user
                // $this->updateToken($loginData['token'], $user);

                // respondemos
                return response()->json([
                    "message" => "Bienvenido",
                    "data" => $loginData,
                    "user" => $user
                ], Response::HTTP_OK);

            }else{
                return response()->json([
                    "message" => "Usuario o Contraseña invalidos",
                    "status" => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            }

        } else {

            //INICIO DE SESION CON GOOGLE 

            $usuario = $user[0];
            if (!empty($usuario->key_google)) {

                //validar key de google
                if($usuario->key_google != $data['key_google']){
                    return response()->json([
                        "message" => "Key de google invalido, no coincide con el registrado",
                        "status" => Response::HTTP_UNAUTHORIZED
                    ], Response::HTTP_UNAUTHORIZED);
                }

                $loginData['token'] = $usuario->createToken("Sharlispat")->accessToken;
            
                // asignamos el token al user
                // $this->updateToken($loginData['token'], $usuario);
    
                // respondemos
                return response()->json([
                    "message" => "Bienvenido",
                    "data" => $loginData,
                    "user" => $usuario
                ], Response::HTTP_OK);

            }else{

                return response()->json([
                    "message" => "Key de google invalido",
                    "status" => Response::HTTP_UNAUTHORIZED
                ], Response::HTTP_UNAUTHORIZED);
            }

        }
    }

    
    /**
     * register user by google.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\ResponseJson
     */
    public function registerGoogle(Request $request)
    {
        $data = $request->all();
      
        $user = User::where('key_google', $data['key_google'])->get();
        $email = User::where('email', $data['email'])->get();

            if(!empty($user[0])){
                return response()->json([
                    "message" => "Este key de google ya existe",
                    "status" => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }
        
            if(!empty($email[0])){
                return response()->json([
                    "message" => "Este E-mail de google ya existe",
                    "status" => Response::HTTP_OK,
                ], Response::HTTP_OK);
            }

        // Procedemos a crear el usuario
        $user = User::create($data);
        
        if (!$user) {
            return response()->json([
                "message" => "Error en el registro de usuario",
                "status" => Response::HTTP_CONFLICT,
            ], Response::HTTP_CONFLICT);
        } else {
            //login
            return $this->login($user->email, null, $user->key_google);
        
        }

    }

    public function register(Request $request)
    {
        $data = $request->all();
        
        $pass = $data['password'] ?? null;

        // encriptamos la contraseña por buena practica
        $data['password'] = bcrypt($data['password']);

        // Procedemos a crear el usuario
        $user = User::create($data);

        //obtenemos los datos del user
        //validamos el resultado para login
        if (empty($user->id)) {
             return response()->json([
                    "message" => "Error en el registro de usuario",
                    "status" => Response::HTTP_CONFLICT,
                ], Response::HTTP_CONFLICT);
        } else {
           
            // login
            return $this->login($data['email'], $pass, null);

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

    public function updateToken($token, $user)
    {

        if($user->update([ "token" => $token ])){
            return true;
        }else{

            return response()->json([
                "message" => "No se actualizo el TOKEN",
                "user" => $user,
                "status" => Response::HTTP_CONFLICT
            ], Response::HTTP_CONFLICT);
            
        }
            
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
