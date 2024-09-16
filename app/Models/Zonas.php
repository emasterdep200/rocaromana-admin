<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Zonas extends Model
{
    use HasFactory;

    protected $table = 'rc_zonas';


    // public function cliente(): BelongsTo{
    //     return $this->belongsTo(Customer::class, 'id_cliente', 'id');
    // }

    // public function package(): BelongsTo{
    //     return $this->belongsTo(Package::class, 'plan', 'id');
    // }

    // public function plan(): BelongsTo{
    //     return $this->belongsTo(Package::class, 'plan', 'id');
    // }

}
