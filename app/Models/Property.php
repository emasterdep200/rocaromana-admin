<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $table = 'propertys';

    protected $fillable = [
        'category_id',
        'title',
        'description',
        'address',
        'client_address',
        'propery_type',
        'price',
        'title_image',
        'state',
        'country',
        'state',
        'status',
        'total_click',
        'latitude',
        'longitude'

    ];
    protected $hidden = [
        'updated_at',
        'deleted_at'
    ];

    protected $appends = [
        'gallery'
    ];

    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id', 'category', 'parameter_types', 'image');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'added_by', 'fcm_id', 'notification');
    }
    public function user()
    {
        return $this->hasMany(User::class, 'id', 'added_by', 'fcm_id', 'notification');
    }

    public function assignParameter()
    {
        return  $this->morphMany(AssignParameters::class, 'modal');
    }

    public function parameters()
    {
        return $this->belongsToMany(parameter::class, 'assign_parameters', 'modal_id', 'parameter_id')->withPivot('value');
    }
    public function assignfacilities()
    {
        return $this->hasMany(AssignedOutdoorFacilities::class);
    }

    public function favourite()
    {
        return $this->hasMany(Favourite::class);
    }
    public function interested_users()
    {
        return $this->hasMany(InterestedUser::class);
    }
    // public function assign_parameter()
    // {
    //     return $this->hasMany(AssignParameters::class);
    // }
    public function advertisement()
    {
        return $this->hasMany(Advertisement::class);
    }

    public function getGalleryAttribute()
    {
        $data = PropertyImages::select('id', 'image')->where('propertys_id', $this->id)->get();


        foreach ($data as $item) {
            if ($item['image'] != '') {
                $item['image'] = $item['image'];
                $item['image_url'] = ($item['image'] != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH') . $this->id . "/" . $item['image'] : '';
            }
        }
        return $data;
    }
    public function getTitleImageAttribute($image)
    {

        return $image != '' ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $image : '';
    }


    public function getMetaImageAttribute($image)
    {

        return $image != '' ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_SEO_IMG_PATH') . $image : '';
    }
    public function getThreeDImageAttribute($threeDimage)
    {
        return $threeDimage != '' ? url('') . config('global.IMG_PATH') . config('global.3D_IMG_PATH') . $threeDimage : '';
    }
    protected $casts = [
        'category_id' => 'integer',
    ];
}
