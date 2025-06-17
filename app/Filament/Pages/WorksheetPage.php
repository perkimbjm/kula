<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;

class WorksheetPage extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-document-duplicate';

    protected static string $view = 'filament.pages.worksheet-page';

    protected static ?string $navigationLabel = 'Kertas Kerja';

    protected static ?string $title = 'Kertas Kerja';

    protected static ?int $navigationSort = 2;
}
