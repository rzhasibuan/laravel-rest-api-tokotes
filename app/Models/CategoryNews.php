<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryNews extends Model
{
    use HasFactory;
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'create' => $this->created_at->format('d M y')
        ];
    }

    public function news()
    {
        return $this->hasMany(News::class,'category_news_id');
    }
}
