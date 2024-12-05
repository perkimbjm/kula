<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkStatus: string implements HasLabel
{
    case belum_kontrak = 'belum_kontrak';
    case kontrak = 'kontrak';
    case selesai = 'selesai';

    public function getLabel(): ?string
    {
        return str(str($this->value)->replace('_', ' '))->title();
    }


}