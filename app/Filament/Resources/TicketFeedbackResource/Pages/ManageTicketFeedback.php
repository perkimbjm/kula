<?php

namespace App\Filament\Resources\TicketFeedbackResource\Pages;

use App\Filament\Resources\TicketFeedbackResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageTicketFeedback extends ManageRecords
{
    protected static string $resource = TicketFeedbackResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
