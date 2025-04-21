<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageAmenityPhoto extends Model
{
    use HasFactory;
    
    public function packageAmenity()
    {
        return $this->belongsTo(PackageAmenity::class);
    }

}
