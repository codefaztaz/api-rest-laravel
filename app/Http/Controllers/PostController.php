<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\Post;
use App\Helpers\JwtAuth;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' =>['index', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all()->load('category');

        return response()->json([
            'code' => 200,
            'status' =>'success',
            'posts' => $posts
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // recoger los datos por post
        $json = $request->input('json', null);
        $params_array = json_decode($json, true);
        $params = json_decode($json); 

        if(!empty($params_array))
        {
            $user = $this->getIdentity($request);
                //validar los datos
            $validate = \Validator::make($params_array, [
                'title' => 'required',
                'content' => 'required',
                'category_id' => 'required',
                'image' =>'required'
            ]);

            // guardar la categoria
            if($validate->fails())
            {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado el post faltan datos'
                ];
            }
            else 
            {
                $post = new Post();
                $post->user_id = $user->sub;
                $post->category_id = $params->category_id;
                $post->title = $params->title;
                $post->content = $params->content;
                $post->image = $params->image;
                $post->save();

                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'post' => $post
                ];
            }


        }
        else
        {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'envia los datos correctamente'
            ];

        }

        
        // devolver resultado
        return response()->json($data, $data['code']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::find($id);

        if(is_object($post))
        {
            $data = [
                'code' => 200,
                'status' =>'success',
                'posts' => $post
            ];
            

        }
        else 
        {
            $data = [
                'code' => 404,
                'status' =>'error',
                'message' => 'el post no existe'
            ];
            

        }

        return response()->json($data, $data['code']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
         // recoger datos por post
         $json = $request->input('json', null);
         $params_array = json_decode($json, true); // con true pasamos un array

         // datos para devolver

         $data = [
            'code' => 400,
            'status' =>'error',
            'message' => 'datos enviados incorrecto'
        ];

 
 
         if(!empty($params_array))
         {
             // valiudar los datos
             $validate = \Validator::make($params_array, [
                 'title' => 'required',
                 'content' =>'required',
                 'category_id' =>'required'
             ]);

             if($validate->fails())
             {
                $data['errors'] = $validate->errors();
                return response()->json($data, $data['code']);
             }
 
             //quitar lo que no quiero actualizar
             unset($params_array['id']);
             unset($params_array['created_at']);
             unset($params_array['user_id']);
             unset($params_array['user']);

            // conseguir usuario identificado
            $user = $this->getIdentity($request);
 
             // actualizar el registro(categoria)
             // buscar el registro
             $post = Post::where('id', $id)->where('user_id', $user->sub)->first();

             if(!empty($post) && is_object($post))
             {
                $where = [
                    'id' => $id,
                    'user_id' => $user->sub
                 ];
    
                 $post = Post::updateOrCreate($where, $params_array);
     
                 $data = [
                     'code' => 200,
                     'status' =>'success',
                     'post' => $post,
                     'post' => $params_array
                 ];
     


             }


         }
    
         // devolver los datos
         return response()->json($data, $data['code']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        // conseguir usuario identificado
        $user = $this->getIdentity($request);

        // conseguir el registro
        $post = Post::where('id', $id)->where('user_id', $user->sub)->first();
        
        if(!empty($post))
        {
            // borrarlo
            $post->delete();
            // devolver algo
            $data = [
                'code' => 200,
                'status' => 'success',
                'post'  => $post
            ];

        }
        else 
        {
            $data = [
                'code' => 404,
                'status' => 'error',
                'message'  => 'el post no existe'
            ];
        }
 

        return response()->json($data, $data['code']);
    }

    private function getIdentity(Request $request)
    {
        $jwtAuth = new JwtAuth();
        $token = $request->header('Authorization', null);
        $user = $jwtAuth->checkToken($token, true);

        return $user;
    }
}
