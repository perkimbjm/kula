<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ContractorResource\Pages;
use App\Filament\Resources\ContractorResource\RelationManagers;
use App\Models\Contractor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ContractorResource extends Resource
{
    protected static ?string $model = Contractor::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('akta')
                    ->required()
                    ->maxLength(255),
                Forms\Components\DatePicker::make('founding_date')
                    ->required(),
                Forms\Components\TextInput::make('notary')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('npwp')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('leader')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('position')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('slug')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_number')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('account_holder')
                    ->required()
                    ->maxLength(255),
                Forms\Components\FileUpload::make('header_scan')
                    ->image()
                    ->directory('kontraktor/kop'),
                Forms\Components\FileUpload::make('account_scan')
                    ->image()
                    ->directory('kontraktor/rek'),
                Forms\Components\FileUpload::make('npwp_scan')
                    ->image()
                    ->directory('kontraktor/npwp'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('akta')
                    ->searchable(),
                Tables\Columns\TextColumn::make('founding_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('notary')
                    ->searchable(),
                Tables\Columns\TextColumn::make('address')
                    ->searchable(),
                Tables\Columns\TextColumn::make('npwp')
                    ->searchable(),
                Tables\Columns\TextColumn::make('leader')
                    ->searchable(),
                Tables\Columns\TextColumn::make('position')
                    ->searchable(),
                Tables\Columns\TextColumn::make('slug')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_holder')
                    ->searchable(),
                Tables\Columns\TextColumn::make('header_scan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('account_scan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('npwp_scan')
                    ->searchable(),
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageContractors::route('/'),
        ];
    }
}
