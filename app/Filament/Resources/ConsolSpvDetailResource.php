<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsolSpvDetailResource\Pages;
use App\Filament\Resources\ConsolSpvDetailResource\RelationManagers;
use App\Models\ConsolSpvDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsolSpvDetailResource extends Resource
{
    protected static ?string $model = ConsolSpvDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('consolidation_id')
                    ->relationship('consolidation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('budget')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nego_value')
                    ->numeric(),
                Forms\Components\Select::make('consol_spv_id')
                    ->relationship('consolSpv', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('consolidation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consolSpv.id')
                    ->numeric()
                    ->sortable(),
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
            'index' => Pages\ListConsolSpvDetails::route('/'),
            'create' => Pages\CreateConsolSpvDetail::route('/create'),
            'edit' => Pages\EditConsolSpvDetail::route('/{record}/edit'),
        ];
    }
}
