<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ConsolPlanDetailResource\Pages;
use App\Filament\Resources\ConsolPlanDetailResource\RelationManagers;
use App\Models\ConsolPlanDetail;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ConsolPlanDetailResource extends Resource
{
    protected static ?string $model = ConsolPlanDetail::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder-plus';

    protected static ?string $navigationGroup = 'Manajemen PBJ';

    protected static ?string $label = 'Detail Konsolidasi Paket Perencanaan';

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
                    ->label('Nama')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('nego_value')
                    ->label('Penawaran')
                    ->numeric(),
                Forms\Components\Select::make('consol_plan_id')
                    ->label('ID Konsolidasi Perencanaan')
                    ->relationship('consolPlan', 'id')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()            ->columns([
                Tables\Columns\TextColumn::make('consolidation.id')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('budget')
                    ->label('Pagu')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->label('Penawaran')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consolPlan.id')
                    ->label('ID Konsolidasi Perencanaan')
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
            'index' => Pages\ListConsolPlanDetails::route('/'),
            'create' => Pages\CreateConsolPlanDetail::route('/create'),
            'edit' => Pages\EditConsolPlanDetail::route('/{record}/edit'),
        ];
    }
}
