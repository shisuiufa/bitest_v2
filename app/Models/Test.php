<?php

namespace App\Models;

use App\Enums\TestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Test extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'title',
        'desc',
        'image',
        'author_id',
        'time_complete',
        'attempts',
        'limit_questions',
        'published',
        'created_at',
        'updated_at',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'test_users');
    }

    public function testUsers(): HasMany
    {
        return $this->hasMany(TestUser::class);
    }

    public function lastTestUser(): Model|null
    {
        $user = auth()->user();

        return $this->testUsers()
            ->where('user_id', $user->id)
            ->latest()
            ->first();
    }

    public function lastResultUser(): Model|null
    {
        $user = auth()->user();

        return $this->testUsers()
            ->where('user_id', $user->id)
            ->whereNot('status', TestStatus::ONGOING)
            ->latest()
            ->first();
    }

}
