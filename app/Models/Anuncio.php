<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Anuncio extends Model
{
    use HasFactory;

    protected $table = 'rc_anuncios';


    protected $fillable = [
        'imagen',
        'titulo',
        'link',
        'owner',
        'estado'
    ];

}
