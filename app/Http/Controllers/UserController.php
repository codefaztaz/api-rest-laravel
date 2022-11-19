<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function pruebas()
    {
        return "controlador de usuario";
    }

    public function showToken()
    {
        echo csrf_token();
    }

    public function register(Request $request)
    {
        // recoger los datos del usuario por post
        $json = $request->input('json', null);
        $params = json_decode($json);//objeto
        $params_array = json_decode($json,true);//array
        var_dump($params_array);

        if(!empty($params) && (!empty($params_array)))
        {

            
            // limpiar datos
            $params_array = array_map('trim', $params_array);

            // validar datos
            $validate = \Validator::make($params_array, [

                'name'    =>    'required|alpha',
                'surname' =>    'required|alpha',
                'email'   =>    'required|email|unique:users',  // comprobar si el usuario existe ya (duplicado)
                'password'=>    'required'
            ]);

            if($validate->fails())
            {
                $data = array(

                    'status' => 'error',
                    'code' => 404,
                    'message' => 'el usuario no se ha creado',
                    'errors' =>$validate->errors()
                );
            
            }
            else
            {
                // cifrar contraseña
                $pwd = password_hash($params->password, PASSWORD_BCRYPT, ['cost' =>4]);

              

                // crear el usuario
                $user = new User();
                $user -> name = $params_array['name'];
                $user -> surname = $params_array['surname'];
                $user -> email = $params_array['email'];
                $user -> password = $pwd;
                $user -> role = 'ROLE_USER';

                $user->save();
        
                $data = array(

                    'status' => 'success',
                    'code' => 200,
                    'message' => 'el usuario  se ha creado correctamente',
                    'user' => $user
                
                );

            }
        
        }
        else
        {
            $data = array(

                'status' => 'error',
                'code' => 404,
                'message' => 'los datos enviados no son correctos',
                
            );
        


        }    


        return response()->json($data, $data['code']);


    }

    public function login(Request $request)
    {
        
    }
}

