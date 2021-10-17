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
    public function editorial(){
        return $this->belongsTo(Editorial::class, 'editorial_id');
    }
    
     //Para que regrese el objeto categoria
     public function categoria(){
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

     
}
