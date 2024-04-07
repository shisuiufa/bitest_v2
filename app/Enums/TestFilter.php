<?php

namespace App\Enums;

enum TestFilter: string
{
    case New = 'new';
    case Category = 'category';
    case Name = 'name';
    case Author = 'author';
}
