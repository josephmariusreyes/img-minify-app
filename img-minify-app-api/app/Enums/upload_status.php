<?php

namespace App\Enums;

enum upload_status: string
{
    case Pending = '1';
    case Active = '2';
    case Done = '3';
}

