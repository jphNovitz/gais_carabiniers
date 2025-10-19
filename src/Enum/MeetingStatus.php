<?php

namespace App\Enum;

enum MeetingStatus: string
{
    case DRAFT = 'draft';           // Séance créée mais non encore préparée
    case READY = 'ready';           // Participants définis, prête à commencer
    case IN_PROGRESS = 'in_progress'; // Séance en cours
    case CLOSED = 'closed';     // Tirs terminés, résultats disponibles
    case ARCHIVED = 'archived';     // Séance clôturée et archivée

}
