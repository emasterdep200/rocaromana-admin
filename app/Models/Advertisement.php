<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advertisement extends Model
{
    use HasFactory;

    protected $fillable = ['status'];

    public function customer()
    {
        return $this->hasOne(Customer::class, 'id','customer_id');
    }
    public function getimageAttribute($image)
    {
        return url('') . config('global.IMG_PATH') . config('global.ARTICLE_IMG_PATH') . $image;
    }
    public function property()
    {
        return $this->hasOne(Property::class, 'id', 'property_id');
    }
}


