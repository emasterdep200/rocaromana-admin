<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectDocuments extends Model
{
    use HasFactory;
    public function getNameAttribute($name)
    {
        return $name != '' ? url('') . config('global.IMG_PATH') . config('global.PROJECT_DOCUMENT_PATH') . $name : '';
    }
}
