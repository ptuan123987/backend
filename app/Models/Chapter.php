<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title'];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function lectures()
    {
        return $this->hasMany(Lecture::class, 'chapter_id');
    }
}
