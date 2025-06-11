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

    protected static ?string $navigationIcon = 'heroicon-s-folder-plus';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Detail Konsolidasi Paket Pengawasan';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('consolidation_id')
                    ->label('ID')
                    ->relationship('consolidation', 'id')
                    ->required(),
                Forms\Components\TextInput::make('budget')
                    ->label('Pagu')
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Paket')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nego_value')
                    ->label('Penawaran')
                    ->numeric(),
                Forms\Components\Select::make('consol_spv_id')
                    ->label('ID Konsolidasi Pengawasan')
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
                    ->label('Pagu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Penawaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consolSpv.id')
                    ->label('ID Konsolidasi Pengawasan')
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
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ViewAction::make(),
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
