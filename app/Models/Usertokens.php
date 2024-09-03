<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Usertokens extends Model
{
    use HasFactory;
    protected $fillable = ['fcm_id','customer_id','api_token'];
}
