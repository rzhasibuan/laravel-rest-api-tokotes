<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = [];
//    berfungsi untuk menambahkan semua yang ada di dalam field karna di dalamnya ada array kosong

//    public function getRouteKeyName()
//    {
//        return 'slug';
//    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
