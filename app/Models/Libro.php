<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Libro extends Model
{
    use HasFactory;

    protected $table = "libros";

    public $timestamps = false;

    protected $fillable =[
        'id',
        'isbn',
        'titulo',
        'descripcion',
        'fecha_publicacion',
        'editorial_id',
        'categoria_id'
    ];


    //Para que regrese el objeto editorial
    public function editoriales(){
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }
    
     //Para que regrese el objeto categoria
     public function categorias(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

     //Se coloca el orden de las tablas
     public function autores(){
        return $this->belongsToMany(
            Autor::class, //La otra tabla con la que tiene relacion
            'autores_libros', //La tabla pivote
            'libros_id', //Donde esta
            'autores_id' //A donde quiere ir
        );
    }  
}
