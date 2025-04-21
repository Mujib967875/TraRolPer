<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class PackageAmenity extends Model
{
    use HasFactory, SoftDeletes;
    public function amenity()
    {
        return $this->belongsTo(Amenity::class);
    }

    public function photo()
    {
        return $this->hasMany(PackageAmenityPhoto::class);
    }

}
