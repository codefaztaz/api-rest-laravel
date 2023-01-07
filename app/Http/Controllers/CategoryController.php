<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Http\Response;
use App\Models\Category;

class CategoryController extends Controller
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
        $categories = Category::all();
        var_dump($categories);
        return response()->json([
            'code' => 200,
            'status' =>'success',
            'categories' => $categories
        ]);
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
        $params_array = json_decode($json, true); // con true pasamos un array

        if(!empty($params_array))
        {
                //validar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            // guardar la categoria
            if($validate->fails())
            {
                $data = [
                    'code' => 400,
                    'status' => 'error',
                    'message' => 'No se ha guardado la categoria'
                ];
            }
            else 
            {
                $category = new Category();
                $category->name = $params_array['name'];
                $category->save();

                $data = [
                    'code' => 200,
                    'status' => 'succes',
                    'category' => $category
                ];
            }


        }
        else
        {
            $data = [
                'code' => 400,
                'status' => 'error',
                'message' => 'No has enviado ninguna categoria'
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
        $category = Category::find($id);

        if(is_object($category))
        {
            $data = [
                'code' => 200,
                'status' =>'success',
                'categories' => $category
            ];
            

        }
        else 
        {
            $data = [
                'code' => 404,
                'status' =>'error',
                'message' => 'La categoria no existe'
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


        if(!empty($params_array))
        {
            // valiudar los datos
            $validate = \Validator::make($params_array, [
                'name' => 'required'
            ]);

            //quitar lo que no quiero actualizar
            unset($params_array['id']);
            unset($params_array['created_at']);

            // actualizar el registro(categoria)
            $category = Category::where('id', $id)->update($params_array);

            $data = [
                'code' => 200,
                'status' =>'success',
                'cateogry' => $params_array
            ];


            // devolver los datos
        }
        else
        {
            $data = [
                'code' => 400,
                'status' =>'error',
                'message' => 'No has enviado ninguna categoria'
            ];

        }

        return response()->json($data, $data['code']);

    }
      

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
