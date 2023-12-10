<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LectureVideo extends Model
{
    use HasFactory;
    protected $fillable = ['url', 'thumbnail_url', 'duration', 'lecture_id'];

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'lecture_id');
    }
}
