<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class CategoriasController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $listaCategoria = DB::table('categorias')->get();
        $message = ($listaCategoria) ? "Consulta exitosa" : "sin registros";
        return response()->json(["error"=>false, "message"=>$message, "categorias"=> $listaCategoria]);
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
                $message = "Registro exitoso";
                $objetoCategoria = new Categoria();
                
            }else{
                $message = "Actualización exitosa";
                $objetoCategoria = Categoria::find($request->id);
            }
            $objetoCategoria->nombre = $request->nombre;
            $objetoCategoria->save();
            DB::commit();
            return response()->json([
                "error"=>false, "message"=>$message, "registro" => $objetoCategoria], 201);

        }catch (QueryException $queryException){
            DB::rollBack();
            return response()->json($queryException->errorInfo,500);
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
        $objetoCategoria = Categoria::find($id);
        if($objetoCategoria){
            return response()->json(["error"=>false, "message"=> "Consulta Exitosa", "Registro" => $objetoCategoria], 200);
        }else{
            return response()->json(["error"=>false, "message"=> "Consulta Exitosa", "Registro" => "Registro inexistente"],200);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $objetoCategoria = Categoria::find($id);
        if($objetoCategoria){
            $objetoCategoria->delete();
            return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
        }else{
            return response()->json(["error" => false, "message" => "Registro inexistente"], 200);
        
        }
    }
}
