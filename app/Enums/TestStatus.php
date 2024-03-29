<?php

namespace App\Enums;

enum TestStatus: string
{
    case PASSED = 'passed';
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
}
