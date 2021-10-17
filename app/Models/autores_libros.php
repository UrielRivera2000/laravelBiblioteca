<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class autores_libros extends Model
{
    use HasFactory;


    
    protected $table = "autores_libros";

    public $timestamps = false;

    protected $fillable =[
        'autores_id',
        'libros_id'
    ]
}
