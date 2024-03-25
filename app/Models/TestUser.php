<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'user_id',
        'score',
        'try',
        'created_at',
        'updated_at',
    ];

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'test_user_questions', 'test_user_id', 'question_id')->withTimestamps();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
