<?php

namespace App\Services;

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
    public function getListTests(string $search, bool $pagination, int $page, int $perPage = 10): LengthAwarePaginator|Collection
    {
        $tests = Test::where('published', 1);

        if (!empty($search)) {
            $tests->where('title', 'like', $search . '%');
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
            $test->time_complete = $info['time'] ?? null;
            $test->attempts = $info['attempts'] ?? null;
            $test->limit_questions = $info['limitQuestions'] ?? null;
            $test->published = $info['published'];
            $test->image = $info['image'];
            $test->save();

            if (!empty($questions)) {
                $questionsDb = $test->questions;

                $clientQuestionIds = array_column($questions, 'id');

                foreach ($questionsDb as $questionDb) {
                    if (!in_array($questionDb->id, $clientQuestionIds)) {
                        $questionDb->delete();
                    } else {
                        foreach ($questions as $question) {
                            if ($question['id'] == $questionDb->id) {
                                $questionType = QuestionType::where('value', $question['type'])->first();
                                $questionDb->name = $question['name'];
                                $questionDb->question_type_id = $questionType->id;
                                $questionDb->save();
                                if ($question['type'] > 0) {
                                    $options = $question['options'];
                                    $optionsIds = array_column($options, 'id');
                                    $optionsDb = $questionDb->options;

                                    foreach ($optionsDb as $optionDb) {
                                        if (!in_array($optionDb->id, $optionsIds)) {
                                            $optionDb->delete();
                                        } else {
                                            foreach ($options as $option) {
                                                if ($option['id'] == $optionDb->id) {
                                                    $optionDb->name = $option['name'];
                                                    $optionDb->correct = $option['correct'];
                                                    $optionDb->save();
                                                }
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

            DB::commit();

            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return false;
        }
    }

    public function getTestUser(int $testId): TestUser
    {
        return auth()->user()->test()
            ->where('test_id', $testId)
            ->whereNull('score')
            ->whereNull('percent')
            ->latest()
            ->first();
    }

    public function createAnswers(TestUser $test, array $answers): void
    {
        try {
            DB::beginTransaction();

            foreach ($answers as $item) {
                $questionId = $item['id'];
                $answers = $item['userAnswer'];
                $correct = null;
                $questionDb = Question::find($questionId);

                if (boolval($questionDb->type->value)) {
                    $correctOptions = $questionDb->options->where('correct', true)->pluck('id')->toArray();
                    $selectedAnswers = $answers['selectedAnswers'];

                    $correct = count(array_diff($correctOptions, $selectedAnswers)) === 0 && count(array_diff($selectedAnswers, $correctOptions)) === 0;

                    if (empty($selectedAnswers) || !$correct) {
                        $correct = false;
                    }
                }

                $userAnswer = new UserAnswer();
                $userAnswer->test_user_id = $test->id;
                $userAnswer->question_id = $questionId;
                $userAnswer->answers = json_encode($answers);
                $userAnswer->correct = $correct;
                $userAnswer->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            abort(400, 'Что-то пошло не так. Попробуйте еще раз.');
        }
    }

    public function checkTest(TestUser $test): void
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
            $test->update();
        }
    }
}
