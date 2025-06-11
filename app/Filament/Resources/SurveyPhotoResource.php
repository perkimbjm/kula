<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Survey;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\SurveyPhoto;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SurveyPhotoResource\Pages;
use App\Filament\Resources\SurveyPhotoResource\RelationManagers;

class SurveyPhotoResource extends Resource
{
    protected static ?string $model = SurveyPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-photo';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Foto-foto Tracking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_id')
                    ->label('Tracking')
                    ->relationship('survey', 'name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->label('Penjelasan Foto')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo_url')
                    ->label('Upload Foto')
                    ->required()
                    ->image()
                    ->directory('survei')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(SurveyPhoto::query())
            ->columns([
                Tables\Columns\TextColumn::make('survey.name')
                    ->label('Nama Tracking')
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_url')
                    ->label('Foto Tracking')
                    ->size(350, 180),
                Tables\Columns\TextColumn::make('description')
                    ->label('Penjelasan Foto')
                    ->limit(50),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->groups([
                Tables\Grouping\Group::make('survey.name')
                    ->label('Nama Tracking')
                    ->collapsible(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyPhotos::route('/'),
            'create' => Pages\CreateSurveyPhoto::route('/create'),
            'edit' => Pages\EditSurveyPhoto::route('/{record}/edit'),
        ];
    }
}
