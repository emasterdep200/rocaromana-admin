<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;


class PubPack extends Model
{
    use HasFactory;

    protected $table = 'rc_pubpack';


    protected $fillable = [
        'nombre',
        'price',
        'days_valid'
    ];

}
