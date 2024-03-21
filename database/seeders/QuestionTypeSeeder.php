<?php

namespace Database\Seeders;

use App\Models\OptionType;
use App\Models\QuestionType;
use App\Models\Role;
use Illuminate\Database\Seeder;

class QuestionTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $typeOpenOption = new QuestionType();
        $typeOpenOption->caption = 'Открытый';
        $typeOpenOption->value = 0;
        $typeOpenOption->save();

        $typeOneOption = new QuestionType();
        $typeOneOption->caption = 'Один вариант';
        $typeOneOption->value = 1;
        $typeOneOption->save();

        $typeSeveralOption = new QuestionType();
        $typeSeveralOption->caption = 'Несколько вариантов';
        $typeSeveralOption->value = 2;
        $typeSeveralOption->save();
    }
}
