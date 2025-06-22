<?php

namespace App\Enums;

enum TrackStatus: string
{
    case BAIK = 'baik';
    case DENGAN_PERBAIKAN = 'dengan_perbaikan';
    case KURANG = 'kurang';

    public function getLabel(): string
    {
        return match ($this) {
            self::BAIK => 'Baik',
            self::DENGAN_PERBAIKAN => 'Dengan Perbaikan',
            self::KURANG => 'Kurang',
        };
    }

    public function getColor(): string
    {
        return match ($this) {
            self::BAIK => 'success',
            self::DENGAN_PERBAIKAN => 'warning',
            self::KURANG => 'danger',
        };
    }
}
