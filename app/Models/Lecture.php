<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lecture extends Model
{
    use HasFactory;
    protected $fillable = ['chapter_id', 'title'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function resources()
    {
        return $this->hasMany(LectureResource::class, 'lecture_id');
    }

    public function video()
    {
        return $this->hasOne(LectureVideo::class, 'lecture_id');
    }
}
