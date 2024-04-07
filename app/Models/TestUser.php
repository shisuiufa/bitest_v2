<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Concerns\HasEvents;

class TestUser extends Model
{
    use HasFactory, HasEvents;

    protected $fillable = [
        'id',
        'test_id',
        'user_id',
        'score',
        'percent',
        'attempt',
        'status',
        'created_at',
        'updated_at',
    ];

    public function test(): BelongsTo
    {
        return $this->belongsTo(Test::class);
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'test_user_questions', 'test_user_id', 'question_id')->withTimestamps();
    }

    public function answers(): HasMany
    {
        return $this->hasMany(UserAnswer::class);
    }
}
