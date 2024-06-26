<?php

namespace App\Models;

use App\Enums\TestStatus;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    public function getCheckedTestProgress(): array|null
    {
        $totalTests = $this->testUsers()->whereNot('status', TestStatus::ONGOING)->count();
        $pendingTests = $this->testUsers()->where('status', TestStatus::PENDING)->count();

        $progress = [
            'total_tests' => $totalTests,
            'pending_tests' => $pendingTests,
            'progress_percentage' => 0,
        ];

        if ($totalTests > 0) {
            $completedTests = $totalTests - $pendingTests;
            $progress['progress_percentage'] = ($completedTests / $totalTests) * 100;
        }

        return $progress;
    }

    public function uniqueUsersByAttemptDesc(): Collection|null
    {
        return $this->testUsers()
            ->whereNot('status', TestStatus::ONGOING)
            ->orderByDesc('attempt')
            ->get()
            ->unique('user_id');
    }

    public function getTestResultsChartData(): array
    {
        $testUsers = $this->uniqueUsersByAttemptDesc();
        $maxScoreCount = $testUsers->where('percent', '>=', 80)->count();
        $avgScoreCount = $testUsers->whereBetween('percent', [68, 80])->count();
        $minScoreCount = $testUsers->whereNotNull('percent')->whereBetween('percent', [0, 68])->count();

        return [
            'labels' => ['Выше 80', 'Выше 68', 'Ниже 68'],
            'datasets' => [
                $maxScoreCount,
                $avgScoreCount,
                $minScoreCount,
            ],
        ];
    }

    public function getTestResultsByMonthChartData(): array
    {
        $monthLabels = [];

        $uniqueUsersByMonth = $this->testUsers()
            ->orderBy('updated_at')
            ->get()
            ->unique(function ($user) {
                return Carbon::parse($user->updated_at)->format('Y-m') . '-' . $user->user_id;
            });

        $uniqueMonths = $uniqueUsersByMonth
            ->groupBy(function ($user) {
                return Carbon::parse($user->updated_at)->format('Y-m');
            })
            ->map(function ($users) {
                return $users->count();
            });

        $uniqueMonths->each(function ($count, $month) use (&$monthLabels) {
            $monthLabels[] = Carbon::createFromFormat('Y-m', $month)->translatedFormat('F');
        });

        return [
            'labels' => $monthLabels,
            'datasets' => $uniqueMonths->values()->all()
        ];
    }

    public function getTestDurationStatistics(): array
    {
        // Fetch users who have attempted the test, sorted by attempt time in descending order
        $testUsers = $this->uniqueUsersByAttemptDesc();

        $durations = [];

        // Calculate duration in minutes for each user's test attempt
        foreach ($testUsers as $user) {
            if ($user->created_at && $user->test_end_at) {
                $durationInSeconds = $user->created_at->diffInSeconds($user->test_end_at);
                $durationInMinutes = $durationInSeconds / 60;
                $durations[] = $durationInMinutes;
            }
        }
       
        // Calculate the minimum, average, and maximum durations
        $minDuration = !empty($durations) ? min($durations) : null;
        $averageDuration = count($durations) > 0 ? array_sum($durations) / count($durations) : null;
        $maxDuration = !empty($durations) ? max($durations) : null;

        // Return the statistics in the desired format
        return [
            'labels' => ['Минимальное', 'Среднее', 'Максимальное'],
            'datasets' => [
                $minDuration,
                $averageDuration,
                $maxDuration,
            ]
        ];
    }
}
