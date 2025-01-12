<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum WorkStatus: string implements HasLabel
{
    case BELUM_KONTRAK = 'belum_kontrak';
    case KONTRAK = 'kontrak';
    case SELESAI = 'selesai';

    public function getLabel(): ?string
    {
        return str(str($this->value)->replace('_', ' '))->title();
    }


}