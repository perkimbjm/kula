<?php

namespace App\Filament\Resources\SurveyPhotoResource\Pages;

use App\Filament\Resources\SurveyPhotoResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyPhotos extends ListRecords
{
    protected static string $resource = SurveyPhotoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
