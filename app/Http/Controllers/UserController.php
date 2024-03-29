<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

      public function register(Request $request) {

        // Recorger los datos del usuario por post
        $json = $request->input('json', null);
        $params = json_decode($json); // objeto
        $params_array = json_decode($json, true); // array 

        if (!empty($params) && !empty($params_array)) 
        {

            // Limpiar datos
            $params_array = array_map('trim', $params_array);

            // Validar datos
            $validate = \Validator::make($params_array, [
                        'name' => 'required|alpha',
                        'surname' => 'required|alpha',
                        'email' => 'required|email|unique:users',
                        'password' => 'required'
            ]);

            if ($validate->fails()) 
            {
                // La validación ha fallado
                $data = array(
                    'status' => 'error',
                    'code' => 404,
                    'message' => 'El usuario no se ha creado',
                    'errors' => $validate->errors()
                );
            } 
            else 
            {
                // Validación pasada correctamente
                // Cifrar la contraseña
                $pwd = hash('sha256', $params->password);

                // Crear el usuario
                $user = new User();
                $user->name = $params_array['name'];
                $user->surname = $params_array['surname'];
                $user->email = $params_array['email'];
                $user->password = $pwd;
                $user->role = 'ROLE_USER';

                // Guardar el usuario
                $user->save();

                $data = array(
                    'status' => 'success',
                    'code' => 200,
                    'message' => 'El usuario se ha creado correctamente',
                    'user' => $user
                );
            }
        } 
        else 
        {
            $data = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'Los datos enviados no son correctos'
            );
        }

        return response()->json($data, $data['code']);
    }
    // public function register(Request $request)
    // {
    //     // recoger los datos del usuario por post
    //     $json = $request->input('json', null);
    //     $params = json_decode($json);//objeto
    //     $params_array = json_decode($json,true);//array
    //     var_dump($params_array);

    //     if(!empty($params) && (!empty($params_array)))
    //     {

            
    //         // limpiar datos
    //         $params_array = array_map('trim', $params_array);

    //         // validar datos
    //         $validate = \Validator::make($params_array, [

    //             'name'    =>    'required|alpha',
    //             'surname' =>    'required|alpha',
    //             'email'   =>    'required|email|unique:users',  // comprobar si el usuario existe ya (duplicado)
    //             'password'=>    'required'
    //         ]);

    //         if($validate->fails())
    //         {
    //             $data = array(

    //                 'status' => 'error',
    //                 'code' => 404,
    //                 'message' => 'el usuario no se ha creado',
    //                 'errors' =>$validate->errors()
    //             );
            
    //         }
    //         else
    //         {
    //             // cifrar contraseña
    //             $pwd = hash('sha256', $params->password);

              

    //             // crear el usuario
    //             $user = new User();
    //             $user -> name = $params_array['name'];
    //             $user -> surname = $params_array['surname'];
    //             $user -> email = $params_array['email'];
    //             $user -> password = $pwd;
    //             $user -> role = 'ROLE_USER';

    //             $user->save();
        
    //             $data = array(

    //                 'status' => 'success',
    //                 'code' => 200,
    //                 'message' => 'el usuario  se ha creado correctamente',
    //                 'user' => $user
                
    //             );

    //         }
        
    //     }
    //     else
    //     {
    //         $data = array(

    //             'status' => 'error',
    //             'code' => 404,
    //             'message' => 'los datos enviados no son correctos',
                
    //         );
        


    //     }    


    //     return response()->json($data, $data['code']);


    // }

    public function login(Request $request) 
    {

        $jwtAuth = new \JwtAuth();

        // Recibir datos por POST
        $json = $request->input('json', null);
        $params = json_decode($json);
        $params_array = json_decode($json, true);

        // Validar esos datos
        $validate = \Validator::make($params_array, [
                    'email' => 'required|email',
                    'password' => 'required'
        ]);

        if ($validate->fails()) 
        {
            // La validación ha fallado
            $signup = array(
                'status' => 'error',
                'code' => 404,
                'message' => 'El usuario no se ha podido identificar',
                'errors' => $validate->errors()
            );
        } 
        else 
        {
            // Cifrar la password
            $pwd = hash('sha256', $params->password);

            // Devolver token o datos
            $signup = $jwtAuth->signup($params->email, $pwd);

            if (!empty($params->gettoken)) 
            {
                $signup = $jwtAuth->signup($params->email, $pwd, true);
            }
        }


        return response()->json($signup, 200);
    }

    public function update(Request $request)
    {
        $token = $request->header('Authorization');

        $jwtAuth = new \JwtAuth();
        $checkToken = $jwtAuth->checkToken($token);

        // recopger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        var_dump($params_array);

        var_dump($checkToken);
     
        if($checkToken && !empty($params_array))
        {
          
       

      
            
            // sacar usuario identificado
            $user = $jwtAuth->checkToken($token, true);
          
            // validar datos
            $validate = \Validator::make($params_array, [

                'name'    =>    'required|alpha',
                'surname' =>    'required|alpha',
                'email'   =>    'required|email|unique:users'.$user->sub // comprobar si el usuario existe ya (duplicado)
        
            ]);
    


            // quitar los campos que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['role']);
            unset($params_array['password']);
            unset($params_array['created_at']);
            unset($params_array['remember_token']);

            // actualizar usuario en la bd
            $user_update = User::where('id', $user->sub)->update($params_array);

            //devolver array con resultado
            $data = array(

                'status' => 'success',
                'code' => 200,
                'user' => $user_update
                
            );
            
        }
        else
        {
            $data = array(

                'status' => 'error',
                'code' => 400,
                'message' => 'el usuario no está identificado'
                
            );
        
        }
        return response()->json($data, $data['code']);
    }

    public function upload(Request $request)
    {
        // recoger datos de la peiticion
        $image = $request->file('file0');

        // validacion de imagen
        $validate = \Validator::make($request->all(),[
            'file0' =>'required|image|mimes:jpg,jpeg,png,gif'
        ]);

        // gardar  la imagen
        if(!$image || $validate->fails())
        {
            $data = array(

                'status' => 'error',
                'code' => 400,
                'user' => 'la imagen no se ha guardado'
                
            );
            
            

            
        }
        else
        {
            $image_name = time().$image->getClientOriginalName();
            \Storage::disk('users')->put($image_name, \File::get($image));

            $data = array(

                'status' => 'success',
                'code' => 200,
                'image' => $image_name
                
            );
            
        }

        // devolver el resultado
        

        return response()->json($data, $data['code']);

    }

    public function getImage($filename)
    {
        $isset = \Storage::disk('users')->exists($filename);
        if($isset)
        {
            $file  = \Storage::disk('users')->get($filename);
            return new Response($file, 200);

        }
        else 
        {
            $data = array(

                'status' => 'error',
                'code' => 400,
                'user' => 'la imagen no se ha guardado'
                
            );
            return response()->json($data, $data['code']);
        }
       
    }

    public function detail($id)
    {

        $user = User::find($id);

        if(is_object($user))
        {
            $data = array(
                'code' => 200,
                'status' => 'success',
                'user' => $user
            );
        }
        else 
        {
            $data = array(

                'status' => 'error',
                'code' => 404,
                'user' => 'el usuario no existe'
                
            );
            

        }
        return response()->json($data, $data['code']);
    }
}


