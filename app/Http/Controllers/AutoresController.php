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
                    $objetoAutor = new Autor();
            }else{
                $objetoAutor = Autor::find($request->id);
                foreach ($objetoAutor->libros as $item) {
                    $objetoAutor->libros()->deteach([$item->id]);
                }
                // die();
            }


            // $objetoAutor = new Autor();
            $objetoAutor->nombre = $request->nombre;
            $objetoAutor->apellido1 = $request->apellido1;
            $objetoAutor->apellido2 = $request->apellido2;
            $objetoAutor->save();

            foreach ($request->libros as $item) {
                $objetoAutor->libros()->attach($item);
            }
          
            DB::commit();
            return response()->json(["error"=>false, "message"=>"Registro exitoso"], 200);
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
        // $objetoAutor::table('autores')
        // ->join('autores_libros', 'autores.id', '=', 'autores_libros.autores_id')
        // ->join('autores_libros', 'users.id', '=', 'orders.user_id')
        $objetoAutor = Autor::with('libros')->where('id',$id)->first();
        // $objetoAutor = Autor::with('libros')->where(function($query){
        //     $query->orderBy("fecha_publicacion", 'DESC');
        //     $query->where("id", $id);
        // })->get();
        return response()->json(["objetoAutor"=>$objetoAutor], 200);
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
        $objetoAutorLibro = DB::select("SELECT * FROM autores_libros WHERE autores_id = ?", [$id]);

        try{
            if($objetoAutor && !$objetoAutorLibro){
                $objetoAutor->delete();
                return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
            }else{
                if($objetoAutor && $objetoAutorLibro){
                    return response()->json(["error" => false, "message" => "No se puede borrar porque tiene libros"], 200);
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
