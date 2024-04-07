<?php

namespace App\Services;

use App\Enums\TestFilter;
use App\Enums\TestStatus;
use App\Models\Option;
use App\Models\QuestionType;
use App\Models\Test;
use App\Models\Question;
use App\Models\TestUser;
use App\Models\UserAnswer;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;

class TestService
{
    public function getListTests(string $search, bool $pagination, int $page, int $perPage = 10, string $filter): LengthAwarePaginator|Collection
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

    public function createTest(array $info, $questions): bool
    {
        try {
            DB::beginTransaction();

            $test = new Test;
            $test->title = $info['title'];
            $test->desc = $info['desc'];
            $test->author_id = $info['userId'];
            $test->time_complete = $info['time'] ?? null;
            $test->attempts = $info['attempts'] ?? null;
            $test->limit_questions = $info['limitQuestions'] ?? null;
            $test->published = $info['published'];
            $test->image = $info['image'];
            $test->save();


            foreach ($questions as $questionData) {
                $questionType = QuestionType::where('value', $questionData['type'])->first();

                $question = $test->questions()->create([
                    'name' => $questionData['name'],
                    'image' => $questionData['img'] ?? null,
                    'question_type_id' => $questionType->id,
                ]);

                if ($questionData['type'] !== 0) {
                    $options = $questionData['options'];
                    foreach ($options as $option) {
                        $question->options()->create([
                            'name' => $option['name'],
                            'correct' => $option['correct'],
                        ]);
                    }
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e->getMessage());

            return false;
        }

    }

    public function editTest(Test $test, array $info, array $questions): bool
    {
        try {
            DB::beginTransaction();

            $test->title = $info['title'];
            $test->desc = $info['desc'];
            $test->time_complete = $info['timeComplete'] ?? null;
            $test->attempts = $info['attempts'] ?? null;
            $test->limit_questions = $info['limitQuestions'] ?? null;
            $test->published = $info['published'];
            $test->image = $info['image'];
            $test->save();

            if (!empty($questions)) {
                $clientQuestionIds = array_column($questions, 'id');
                $questionsDb = $test->questions;

                foreach ($questionsDb as $questionDb) {
                    if (!in_array($questionDb->id, $clientQuestionIds)) {
                        $questionDb->delete();
                    } else {
                        foreach ($questions as $question) {
                            if ($question['id'] === $questionDb->id) {
                                $questionType = QuestionType::where('value', $question['type'])->first();
                                $questionDb->name = $question['name'];
                                $questionDb->question_type_id = $questionType->id;

                                if (!empty($question['img'])) {
                                    $questionDb->image = $question['img'];
                                }

                                $questionDb->save();
                                $questionDb->fresh();

                                if ($question['type'] > 0) {
                                    $options = $question['options'];

                                    if (!empty($options)) {
                                        $optionsIds = array_column($options, 'id');
                                        $optionsDb = $questionDb->options;

                                        foreach ($optionsDb as $optionDb) {
                                            if (!in_array($optionDb->id, $optionsIds)) {
                                                $optionDb->delete();
                                            } else {
                                                foreach ($options as $option) {
                                                    if ($option['id'] === $optionDb->id) {
                                                        $optionDb->name = $option['name'];
                                                        $optionDb->correct = $option['correct'];
                                                        $optionDb->question_id = $question['id'];
                                                        $optionDb->save();
                                                    }
                                                }
                                            }
                                        }

                                        foreach ($options as $option) {
                                            $existingOption = $optionsDb->firstWhere('id', $option['id']);

                                            if (!$existingOption) {
                                                dd($existingOption);
                                                $newOption = new Option();
                                                $newOption->name = $option['name'];
                                                $newOption->correct = $option['correct'];
                                                $newOption->question_id = $question['id'];
                                                $newOption->save();
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }

                foreach ($questions as $question) {
                    $existingQuestion = $questionsDb->firstWhere('id', $question['id']);
                    if (!$existingQuestion) {
                        $questionType = QuestionType::where('value', $question['type'])->first();

                        $newQuestion = new Question();
                        $newQuestion->name = $question['name'];
                        $newQuestion->question_type_id = $questionType->id;
                        $newQuestion->test_id = $test->id;
                        $newQuestion->save();
                        $newQuestion->fresh();

                        if ($question['type'] > 0) {
                            $options = $question['options'];

                            foreach ($options as $option) {
                                $newOption = new Option();
                                $newOption->name = $option['name'];
                                $newOption->correct = $option['correct'];
                                $newOption->question_id = $newQuestion->id;
                                $newOption->save();
                            }
                        }
                    }
                }
            }

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return false;
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
            $test->update();
            $test->fresh();
        } else {
            $test->status = TestStatus::PENDING->value;
            $test->update();
            $test->fresh();
        }

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
