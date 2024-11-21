<?php

namespace App\Enum;

enum Statut: string
{
    case PENDING = 'PENDING';
    case IN_PROGRESS = 'IN_PROGRESS';
    case COMPLETED = 'COMPLETED';
    case FAILED = 'FAILED';
}
