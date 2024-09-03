<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class Asesor extends Model
{
    use HasFactory;

    protected $table = 'rc_asesores';

    public function ventas(): HasMany{
        return $this->hasMany(Ventas::class, 'id_asesor', 'id');
    }

}
