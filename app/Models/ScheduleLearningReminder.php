<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleLearningReminder extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'time',
        'frequency',
        'days',
        'message'
    ];
    protected $casts = [
        'days' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
