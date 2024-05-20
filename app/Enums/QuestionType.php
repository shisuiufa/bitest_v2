<?php

namespace App\Enums;

enum QuestionType: int
{
    case Open = 0;
    case OneOption = 1;
    case SeveralOptions = 2;
}
