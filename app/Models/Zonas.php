<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Zonas extends Model
{
    use HasFactory;

    protected $table = 'rc_zonas';


    public function ciudades(): BelongsTo{
        return $this->belongsTo(Ciudades::class, 'id', 'id_zona');
    }

}
