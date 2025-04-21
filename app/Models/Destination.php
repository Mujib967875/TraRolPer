<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Destination extends Model
{
    use HasFactory, SoftDeletes;

    public function photos()
    {
        return $this->hasMany(DestinationPhoto::class);
    }

    public function videos()
    {
        return $this->hasMany(DestinationVideo::class);
    }

    public function packages()
    {
        return $this->hasMany(Package::class);
    }
}
