<?php

namespace App\Http\Controllers;

use App\Models\Editorial;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;


class EditorialesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $listaEditorial = DB::table('editoriales')->get();
        $message = ($listaEditorial) ? "Consulta exitosa" : "sin registros";
        return response()->json(["error"=>false, "message"=>$message, "editoriales"=> $listaEditorial]);
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
                $objetoEditorial = new Editorial();
                
            }else{
                $message = "Actualización exitosa";
                $objetoEditorial = Editorial::find($request->id);
            }
            $objetoEditorial->nombre = $request->nombre;
            $objetoEditorial->save();
            DB::commit();
            return response()->json([
                "error"=>false, "message"=>$message, "registro" => $objetoEditorial], 201);

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
        $objetoEditorial = Editorial::find($id);
        if($objetoEditorial){
            return response()->json(["error"=>false, "message"=> "Consulta Exitosa", "Registro" => $objetoEditorial], 200);
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
        $objetoEditorial = Editorial::find($id);
        if($objetoEditorial){
            $objetoEditorial->delete();
            return response()->json(["error" => false, "message" => "Eliminacion exitosa"], 200);
        }else{
            return response()->json(["error" => false, "message" => "Registro inexistente"], 200);
        
        }
    }
}
