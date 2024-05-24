<?php

namespace Database\Seeders;

use App\Enums\QuestionEnum;
use App\Models\QuestionType;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeOpen = new QuestionType();
        $typeOpen->caption = 'Открытый';
        $typeOpen->value = QuestionEnum::Open->value;
        $typeOpen->save();

        $typeClose = new QuestionType();
        $typeClose->caption = 'Закрытый';
        $typeClose->value = QuestionEnum::Close->value;
        $typeClose->save();
    }
}
