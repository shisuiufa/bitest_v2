<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'name',
        'image',
        'question_type_id',
        'test_id'
    ];

    public function test(): belongsTo
    {
        return $this->belongsTo(Test::class);
    }
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
    public function type(): belongsTo
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

}
