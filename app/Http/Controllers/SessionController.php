<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SendTokenResetPassword;
use App\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Bridge\AccessToken;
use Laravel\Passport\Token;
use Illuminate\Support\Facades\Mail;


class SessionController extends Controller
{
    
    protected $newPassword;
    protected $header;
    protected $payload;

    public function putUserPassword(Request $request){
       
        $token = $request->bearerToken();
        $resultAuth = $this->authorizePasswordChange($token);
        // si esta autorisado procede
        if($resultAuth){
            $user = User::where('id', $this->payload->sub)->update([
                "password" =>  Hash::make($request->password)
            ]);
        }
    
        // eliminamos el token de reset password
        $result = $this->destroySession($this->payload->sub);

        if($result){
            return response()->json([
                "message" => "Recuperación de contraseña exitosa",
                "status" => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function sendEmailResetPassword(){
        // recibimos el email del solicitante 
        $email = request('email');

        // validamos que exista el correo
        //obtenemos los datos del correo google
        $users = User::where("email", $email)->get();
        $user = $users[0];
            // validamos si el correo existe     
            if (empty($user)) {
                return response()->json([
                    "message" => "Usuario o Email no existe",
                    "status" => Response::HTTP_OK
                ], Response::HTTP_OK);
            }
        
            // creamos un token de reset password
            $token = $user->createToken("Sharlispat")->accessToken;
            // return $token;
            // asignamos el token al user
            $this->updateToken($token, $user);
            
            $data=[
                "token" => $token,
                "idUser" => $user->id
            ];

           
            // enviamos un correo al solicitante con el token
            Mail::to($email)->send(new SendTokenResetPassword($data));
            
            // respuesta al form
            return response()->json([
                "message" => "Hemos enviado un correo con el link de recuperación de contraseña", 
                "status" => Response::HTTP_OK
            ], Response::HTTP_OK);
    }

    public function authorizePasswordChange($token){
     
        // desencriptamos los dos elementos del token
        $this->setToken($token);
      
        //validar si el token sigue siendo valido
        $tokenExiste = Token::where('id', $this->payload->jti)->get();
        $tokenValido = $tokenExiste[0] ?? false;
        //respuesta
        if($tokenValido){
            return true;
        }else{
            return response()->json([
                "message" => "El token a expirado o no valido", 
                "status" => Response::HTTP_OK
            ], Response::HTTP_OK);
        }
    }

    public function setToken($token){

        // dividimos el token en un array
        $tokenArray = explode('.', $token);

        // desencriptamos los dos elementos del token
        $header =  json_decode(base64_decode($tokenArray[0]));
        $payload =  json_decode(base64_decode($tokenArray[1]));

        $this->header = $header;
        $this->payload = $payload;
       
    }

    public function deleteToken($idUser = null){
        
        // consultamos su token
        $userToken = Token::where('user_id', $idUser)->get();
        $token = $userToken[0];
        
        //validamos que este token sea del usuario correcto
        if ($token->user_id == $idUser) {
            foreach ($userToken as $user) {
                $action = Token::destroy($user->id);
            }
            // eliminamos el token de acceso
            //retornamos una respuesta al from
            if($action){
                return true;
            }else{
                return response()->json([
                    "message" => "Fallo al intentar eliminar token",
                    "status" => Response::HTTP_NOT_FOUND
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }

    public function destroySession($idUserLiteral = null){
         // recibimos el identificador del usario que esta logeado
         $idUser = request('idUser') ?? $idUserLiteral;

        $action = $this->deleteToken($idUser);

        if($action){
            return response()->json([
                "message" => "Sesión cerrada exitosamente",
                "status" => Response::HTTP_OK
            ], Response::HTTP_OK);
        }else{
            return response()->json([
                "message" => "Fallo el cierre de sesión :(",
                "status" => Response::HTTP_NOT_FOUND
            ], Response::HTTP_NOT_FOUND);
        }
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

}
