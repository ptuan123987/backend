<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginRecords extends Model
{
    use HasFactory;
    protected $table = 'login_records';
    protected $fillable = [
        'user_id',
        'login_time',
        'created_at',
        'updated_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
