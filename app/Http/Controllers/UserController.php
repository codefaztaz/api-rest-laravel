<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
        
        

        $data = array(

            'status' => 'error',
            'code' => 404,
            'message' => 'el usuario no se ha creado'
        );

        return response()->json($data, $data['code']);


    }

    public function login(Request $request)
    {
        
    }
}


