<?php

namespace App\Services;

use App\Enums\QuestionEnum;
use App\Enums\TestFilter;
use App\Enums\TestStatus;
use App\Models\Option;
use App\Models\QuestionType;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestUser;
use App\Models\UserAnswer;
use Exception;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TestService
{
    public function getListTests(string $search, bool $pagination, int $page, int $perPage = 10, string|null $filter): LengthAwarePaginator|Collection
    {
        $tests = Test::where('published', 1);

        if (!empty($search)) {
            $tests->where('title', 'like', $search . '%');
        }

        if ($filter === TestFilter::New->value) {
            $tests->orderBy('created_at', 'desc');
        } else if ($filter === TestFilter::Name->value) {
            $tests->orderBy('title', 'asc');
        } else if ($filter === TestFilter::Author->value) {
            $tests->leftJoin('users', 'users.id', '=', 'author_id')
                ->orderBy('last_name', 'asc');
        }

        if ($pagination) {
            return $tests->paginate($perPage, ['*'], 'page', $page);
        }

        return $tests->get();
    }

    public function getTestsAuthor(int $authorId, string $search, bool $pagination, int $page, int $perPage = 10): LengthAwarePaginator|Collection
    {

        $tests = Test::where('author_id', $authorId);

        if (!empty($search)) {
            $tests->where('title', 'like', $search . '%');
        }

        if ($pagination) {
            return $tests->paginate($perPage, ['*'], 'page', $page);
        }

        return $tests->get();

    }

    public function createTest($info, $questions): void
    {
        try {
            DB::beginTransaction();

            $test = Test::create($info->toArray());

            foreach ($questions as $item) {
                $question = $test->questions()->create([
                    'name' => $item['name'],
                    'image' => $item['image'] ?? null,
                    'question_type_id' => $item['question_type_id'],
                ]);

                if ($item['question_type_id'] === QuestionEnum::Close->value) {
                    $options = $item['options'];

                    foreach ($options as $option) {
                        $question->options()->create([
                            'name' => $option['name'],
                            'correct' => $option['value'],
                        ]);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());
        }

    }

    public function editTest(Test $test, $info, $questions): void
    {
        try {
            DB::beginTransaction();

            $test->update($info->toArray());

            $questionIds = collect($questions)->pluck('id')->filter()->toArray();

            $test->questions()->whereNotIn('id', $questionIds)->delete();

            $questionsDb = $test->questions();

            foreach ($questions as $question) {

                $updatedQuestion = $questionsDb->updateOrCreate(
                    [
                        'id' => $question['id'] ?? null
                    ],
                    [
                        'name' => $question['name'],
                        'question_type_id' => $question['question_type_id'],
                        'image' => $question['image'],
                    ]
                );

                $questionDb = $test->questions()->where('id', $updatedQuestion->id)->first();

                if ($question['question_type_id'] === QuestionEnum::Close->value) {
                    $optionsIds = collect($question['options'])->pluck('id')->filter()->toArray();
                    $questionDb->options()->whereNotIn('id', $optionsIds)->delete();

                    foreach ($question['options'] as $option) {
                        $questionDb->options()->updateOrCreate(
                            [
                                'id' => $option['id'] ?? null
                            ],
                            [
                                'name' => $option['name'],
                                'correct' => $option['value'],
                            ]
                        );
                    }
                } else {
                    $questionDb->options()->delete();
                }
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }

    public function getTestUser(int $testId): TestUser|null
    {
        return auth()->user()->test()
            ->where('test_id', $testId)
            ->latest()
            ->first();
    }

    public function manipulateAnswer(TestUser $test, int $questionId, array|null $answers): void
    {
        if (empty($answers['selectedAnswers']) && empty($answers['openAnswer'])) {
            UserAnswer::where('test_user_id', $test->id)
                ->where('question_id', $questionId)
                ->delete();
        } else {
            $questionDb = Question::find($questionId);
            $correct = null;

            if (boolval($questionDb->type->value)) {
                $correctOptions = $questionDb->options->where('correct', true)->pluck('id')->toArray();
                $selectedAnswers = $answers['selectedAnswers'];

                $correct = count(array_diff($correctOptions, $selectedAnswers)) === 0 && count(array_diff($selectedAnswers, $correctOptions)) === 0;

                if (empty($selectedAnswers) || !$correct) {
                    $correct = false;
                }
            }

            $userAnswer = $test->answers()
                ->where('question_id', $questionId)
                ->first();

            if (empty($userAnswer)) {
                $userAnswer = new UserAnswer();
                $userAnswer->test_user_id = $test->id;
                $userAnswer->question_id = $questionId;
                $userAnswer->answers = json_encode($answers);
                $userAnswer->correct = $correct;
                $userAnswer->save();
            } else {
                $userAnswer->answers = json_encode($answers);
                $userAnswer->correct = $correct;
                $userAnswer->update();
            }
        }
    }

    public function checkTest(TestUser $test): TestUser
    {
        $questions = $test->questions;
        $userAnswers = $test->answers;

        $totalQuestions = $questions->count();
        $correctAnswers = 0;
        $openAnswer = false;

        foreach ($userAnswers as $answer) {
            if ($answer->correct === null) {
                $openAnswer = true;
            } elseif ($answer->correct) {
                $correctAnswers++;
            }
        }

        if (!$openAnswer) {
            $percentageCorrect = ($correctAnswers / $totalQuestions) * 100;

            switch (true) {
                case $percentageCorrect >= 80:
                    $score = 5;
                    break;
                case $percentageCorrect >= 68:
                    $score = 4;
                    break;
                case $percentageCorrect >= 56:
                    $score = 3;
                    break;
                default:
                    $score = 2;
                    break;
            }

            $test->score = $score;
            $test->percent = $percentageCorrect;
            $test->status = TestStatus::PASSED->value;
        } else {
            $test->status = TestStatus::PENDING->value;
        }

        if (empty($test->test_end_at)) {
            $test->test_end_at = now();
        }

        $test->update();
        $test->fresh();

        return $test;
    }

    public function getRemainingTimeForTest(TestUser $testUser): int
    {
        $test = $testUser->test;
        $startTime = $testUser->created_at;
        $endTime = $startTime->addSeconds($test->time_complete);

        return now()->diffInSeconds($endTime, false);
    }

    public function checkTimeTest(TestUser $testUser): TestUser
    {
        if (!empty($testUser->test->time_complete)) {
            $remainingSeconds = $this->getRemainingTimeForTest($testUser);

            if ($remainingSeconds < 0) {
                $testUser = $this->checkTest($testUser);
            }
        }
        return $testUser;
    }
}
