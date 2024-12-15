<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Work;
use Filament\Tables;
use Filament\Forms\Form;
use App\Enums\WorkStatus;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\WorkResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\WorkResource\RelationManagers;

class WorkResource extends Resource
{
    protected static ?string $model = Work::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Kemajuan Pekerjaan Fisik';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('contract_date'),
                Forms\Components\TextInput::make('contract_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('contractor_id')
                    ->relationship('contractor', 'name')
                    ->required(),
                Forms\Components\Select::make('consultant_id')
                    ->relationship('consultant', 'name')
                    ->required(),
                Forms\Components\Select::make('supervisor_id')
                    ->relationship('supervisor', 'name')
                    ->required(),
                Forms\Components\TextInput::make('contract_value')
                    ->prefix('Rp.')
                    ->numeric(),
                Forms\Components\TextInput::make('progress')
                    ->suffix('%')
                    ->numeric()
                    ->default(0),
                Forms\Components\DatePicker::make('cutoff')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->enum(WorkStatus::class)
                    ->options(WorkStatus::class)
                    ->required()
                    ->default('belum_kontrak'),
                Forms\Components\TextInput::make('paid')
                    ->prefix('Rp.')
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contract_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('supervisor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('contract_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('progress')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cutoff')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable(),
                Tables\Columns\TextColumn::make('paid')
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
            'index' => Pages\ListWorks::route('/'),
            'create' => Pages\CreateWork::route('/create'),
            'edit' => Pages\EditWork::route('/{record}/edit'),
        ];
    }
}
