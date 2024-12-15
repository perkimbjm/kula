<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SurveyPhotoResource\Pages;
use App\Filament\Resources\SurveyPhotoResource\RelationManagers;
use App\Models\SurveyPhoto;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SurveyPhotoResource extends Resource
{
    protected static ?string $model = SurveyPhoto::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Foto-foto Survei';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('survey_id')
                    ->relationship('survey', 'name')
                    ->required(),
                Forms\Components\Textarea::make('description')
                    ->required()
                    ->columnSpanFull(),
                Forms\Components\FileUpload::make('photo_url')
                    ->required()
                    ->image()
                    ->directory('survei')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('survey.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('photo_url'),
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
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
