<?php

namespace App\Http\Controllers;

use App\Models\Autor;
use App\Models\Libro;
use App\Models\Categoria;
use App\Models\Editorial;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LibrosController extends Controller
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

                
                    $objetoLibro = new Libro();
                    $objetoLibro2 = Libro::where('isbn', $request->isbn)->first();
                
                if($objetoLibro2){
                    return response()->json(["error"=>false, "message"=>"El ISBN ya existe"], 200);
                }

                    $message="Registro Exitoso";
            }else{
                $objetoLibro = Libro::find($request->id);
                foreach ($objetoLibro->autores as $item) {
                    $objetoLibro->autores()->deteach([$item->id]);
                }
                
                $message="Actualización Exitosa";
            }

            $objetoLibro->isbn = $request->isbn;
            $objetoLibro->titulo = $request->titulo;
            $objetoLibro->descripcion = $request->descripcion;
            $objetoLibro->fecha_publicacion = $request->fecha_publicacion;
            $objetoLibro->editorial_id = $request->editorial_id;
            $objetoLibro->categoria_id = $request->categoria_id;
            $objetoLibro->save();

            foreach ($request->autores as $item) {
                $objetoLibro->autores()->attach($item);
            }
            DB::commit();
            return response()->json(["error"=>false, "message"=>$message, "registro" => $objetoLibro], 200);
        }catch(QueryException $queryException){
            DB::rollBack();
            return response()->json(["error"=>true, "message"=>"Ocurrio un error", "El error es: "=>$queryException], 500);
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
        $objetoLibro = Libro::with('autores','categorias', 'editoriales')->where('id',$id)->first();
        return response()->json(["objetoLibro"=>$objetoLibro], 200);
    }



    /**
     * Display the specified resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function showByIsbnOrTitle(Request $request)
    {
       $listaLibros = Libro::where('isbn', 'like', '%'.$request->criterio.'%')
       ->orWhere('titulo', 'like', '%'.$request->criterio.'%')->get();
        return response()->json(["error"=>false, "message"=>"Exito", "lista"=>$listaLibros], 200);
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showByYear($year)
    {
        $listLibros = Libro::with('autores','categorias', 'editoriales')->whereYear('fecha_publicacion', $year)->orderBy('titulo', 'asc')->get();
        return response()->json(["Libros"=>$listLibros], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $objetoLibro = Libro::find($id);
        if(sizeof($objetoLibro->autores)==0){
            $objetoLibro->delete();
            return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
        }else{
            return response()->json(["error" => false, "message" => "No se puede borrar porque tiene autores"], 200);
        }

      
    }


    public function unique(){

        $libros = $libros->unique('isbn');
        return response()->json(["Libros"=>$libros], 200);
    }
    
}
