<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Autor extends Model
{
    use HasFactory;
    
        protected $table = "autores";

        public $timestamps = false;

        protected $fillable =[
            'id',
            'nombre',
            'apellido1',
            'apellido2'
        ];

    //Se coloca el orden de las tablas
    public function libros(){
        return $this->belongsToMany(
            Libro::class, //La otra tabla con la que tiene relacion
            'autores_libros', //La tabla pivote
            'autores_id', //Donde esta
            'libros_id' //A donde quiere ir
        );
    }

}
