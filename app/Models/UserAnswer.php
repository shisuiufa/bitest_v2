<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'test_user_id',
        'question_id',
        'answers',
    ];

    protected $casts = [
        'correct' => 'boolean',
    ];

}
