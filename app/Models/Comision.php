<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Comision extends Model
{
    use HasFactory;

    protected $table = 'rc_comisiones';


    public function asesor(): BelongsTo{
        return $this->belongsTo(Asesor::class, 'id_asesor', 'id');
    }

}
