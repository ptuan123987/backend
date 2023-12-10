<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'price', 'author'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'course_category', 'course_id', 'category_id');
    }

    public function reviews()
    {
        return $this->hasMany(CourseReview::class, 'course_id');
    }

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'course_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
    }
}
