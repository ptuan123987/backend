<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EnrollmentProgress extends Model
{
    use HasFactory;
    protected $fillable = ['enrollment_id', 'completed_lecture'];

    public function enrollment()
    {
        return $this->belongsTo(Enrollment::class, 'enrollment_id');
    }

    public function lecture()
    {
        return $this->belongsTo(Lecture::class, 'completed_lecture');
    }
}
