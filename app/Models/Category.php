<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = ['parent_category_id', 'name'];

    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'parent_category_id');
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_category', 'category_id', 'course_id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, 'topic_category', 'category_id', 'topic_id');
    }
}
