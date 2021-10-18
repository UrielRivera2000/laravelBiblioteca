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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        DB::beginTransaction();
        try{

            if(!$request->id){
                    $objetoLibro = new Libro();
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        $objetoLibroAutor = DB::select("SELECT * FROM autores_libros WHERE libros_id = ?", [$id]);

        try{
            if($objetoLibro && !$objetoLibroAutor){
                $objetoLibro->delete();
                return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
            }else{
                if($objetoLibro && $objetoLibroAutor){
                    return response()->json(["error" => false, "message" => "No se puede borrar porque tiene autores"], 200);
                }else{
                    return response()->json(["error" => false, "message" => "Registro inexistente"], 200);
                }
               
            
            }
        }catch (QueryException $queryException){
            DB::rollBack();
            return response()->json($queryException->errorInfo,500);
        }
      
    }
    
}
