<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'topic_category', 'topic_id', 'category_id');
    }
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
