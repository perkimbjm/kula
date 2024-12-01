<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PlanResource\Pages;
use App\Filament\Resources\PlanResource\RelationManagers;
use App\Models\Plan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('year')
                    ->required()
                    ->numeric(),
                Forms\Components\Select::make('procurement_officer_id')
                    ->relationship('procurementOfficer', 'name')
                    ->required(),
                Forms\Components\TextInput::make('oe')
                    ->numeric(),
                Forms\Components\TextInput::make('bid_value')
                    ->numeric(),
                Forms\Components\TextInput::make('correction_value')
                    ->numeric(),
                Forms\Components\TextInput::make('nego_value')
                    ->numeric(),
                Forms\Components\Select::make('consultant_id')
                    ->relationship('consultant', 'name')
                    ->required(),
                Forms\Components\DatePicker::make('invite_date')
                    ->required(),
                Forms\Components\DatePicker::make('evaluation_date')
                    ->required(),
                Forms\Components\DatePicker::make('nego_date')
                    ->required(),
                Forms\Components\DatePicker::make('BAHPL_date')
                    ->required(),
                Forms\Components\DatePicker::make('sppbj_date')
                    ->required(),
                Forms\Components\DatePicker::make('spk_date')
                    ->required(),
                Forms\Components\TextInput::make('account_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('duration')
                    ->required()
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
                Tables\Columns\TextColumn::make('procurementOfficer.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('oe')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('bid_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('correction_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_value')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('invite_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('evaluation_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nego_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('BAHPL_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('sppbj_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('spk_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('account_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->searchable(),
                Tables\Columns\TextColumn::make('duration')
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
            'index' => Pages\ListPlans::route('/'),
            'create' => Pages\CreatePlan::route('/create'),
            'edit' => Pages\EditPlan::route('/{record}/edit'),
        ];
    }
}