<?php

namespace App\Enum;

enum MeetingType: string
{
    case COMPETITION = 'Tir du mois';
    case PUBLIC = 'Tir public';
    case OTHER = 'Autre';

}
