<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum ProgressStatus: string implements HasLabel
{
    case BERJALAN = 'berjalan';
    case KRITIS = 'kritis';
    case SELESAI = 'selesai';

    public function getLabel(): ?string
    {
        return str(str($this->value)->replace('_', ' '))->title();
    }


}