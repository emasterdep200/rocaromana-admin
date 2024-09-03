<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
    use Carbon\Carbon;

class Projects extends Model
{
    use HasFactory;


    public function category()
    {
        return $this->hasOne(Category::class, 'id', 'category_id')->select('id', 'category', 'parameter_types', 'image');
    }
    public function customer()
    {
        return $this->hasOne(Customer::class, 'id', 'added_by');
    }
    public function gallary_images()
    {
        return $this->hasMany(ProjectDocuments::class, 'project_id')->where('type', 'image');
    }
    public function documents()
    {
        return $this->hasMany(ProjectDocuments::class, 'project_id')->where('type', 'doc');
    }
    public function plans()
    {
        return $this->hasMany(ProjectPlans::class, 'project_id');
    }
    public function getImageAttribute($image, $fullUrl = true)
    {
        if ($fullUrl) {
            return $image != '' ? url('') . config('global.IMG_PATH') . config('global.PROJECT_TITLE_IMG_PATH') . $image : '';
        } else {
            return $image;
        }
    }
 public function getMetaImageAttribute($image, $fullUrl = true)
    {
        if ($fullUrl) {
            return $image != '' ? url('') . config('global.IMG_PATH') . config('global.PROJECT_SEO_IMG_PATH') . $image : '';
        } else {
            return $image;
        }
    }

public function getCreatedAtAttribute($date){
    // Assuming $date is a string representing a date
    $carbonDate = new Carbon($date);

    return $carbonDate->diffForHumans();
}
}

