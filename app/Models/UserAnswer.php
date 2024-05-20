<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'test_user_id',
        'question_id',
        'answers',
        'correct',
    ];

    protected $casts = [
        'correct' => 'boolean',
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function testUser(): BelongsTo
    {
        return $this->belongsTo(TestUser::class, 'test_user_id');
    }
}
