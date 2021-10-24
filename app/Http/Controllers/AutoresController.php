<?php

namespace App\Http\Controllers;


use App\Models\Autor;
use App\Models\Libro;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AutoresController extends Controller
{
    

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try{

            if(!$request->id){
                    $objetoAutor = new Autor();
                    $message="Registro Exitoso";
            }else{
                $objetoAutor = Autor::find($request->id);
                foreach ($objetoAutor->libros as $item) {
                    $objetoAutor->libros()->deteach([$item->id]);
                }
                $message="Actualización Exitosa";
            }
            $objetoAutor->nombre = $request->nombre;
            $objetoAutor->apellido1 = $request->apellido1;
            $objetoAutor->apellido2 = $request->apellido2;
            $objetoAutor->save();

            foreach ($request->libros as $item) {
                $objetoAutor->libros()->attach($item);
            }
          
            DB::commit();
            return response()->json(["error"=>false, "message"=>$message,"registro" => $objetoAutor], 200);
        }catch(QueryException $queryException){
            DB::rollBack();
            return response()->json(["error"=>true, "message"=>$queryException], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $objetoAutor = Autor::with(['libros' => function($query){
            $query->orderBy('fecha_publicacion', 'DESC');
        }])->where('id', $id)->first();

        return response()->json(["error"=>false, "objetoAutor"=>$objetoAutor], 200);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $objetoAutor = Autor::find($id);
        if(sizeof($objetoAutor->libros)==0){
            $objetoAutor->delete();
            return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
        }else{
            return response()->json(["error" => false, "message" => "No se puede borrar porque tiene libros"], 200);
        }
    
    }
}
