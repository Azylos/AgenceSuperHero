<?php

namespace App\Enum;

enum Statut: string
{
    case PENDING = 'EN ATTENTE';
    case IN_PROGRESS = 'EN COURS';
    case COMPLETED = 'TERMINÉ';
    case FAILED = 'ÉCHOUÉ ';
}
