<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum TicketStatus: string implements HasLabel
{
    case OPEN = 'open';
    case CLOSED = 'closed';
    case IN_PROGRESS = 'in_progress';

    public function getLabel(): ?string
    {
        return str(str($this->value)->replace('_', ' '))->title();
    }


}