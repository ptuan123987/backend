<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CourseReview extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'user_id', 'rating', 'content', 'created_at','content', 'parent_id'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function replies()
    {
        return $this->hasMany(CourseReview::class, 'parent_id');
    }

    public function parent()
    {
        return $this->belongsTo(CourseReview::class, 'parent_id');
    }
}
