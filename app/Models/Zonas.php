<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Zonas extends Model
{
    use HasFactory;

    protected $table = 'rc_zonas';


    public function ciudades(){
        return $this->hasMany(Ciudades::class, 'id_zona', 'id');
    }

}
