<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FacilityResource\Pages;
use App\Filament\Resources\FacilityResource\RelationManagers;
use App\Models\Facility;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacilityResource extends Resource
{
    protected static ?string $model = Facility::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('contractor_id')
                    ->relationship('contractor', 'name')
                    ->required(),
                Forms\Components\Select::make('consultant_id')
                    ->relationship('consultant', 'name')
                    ->required(),
                Forms\Components\Select::make('district_id')
                    ->relationship('district', 'name')
                    ->required(),
                Forms\Components\Select::make('village_id')
                    ->relationship('village', 'name')
                    ->required(),
                Forms\Components\TextInput::make('length')
                    ->numeric(),
                Forms\Components\TextInput::make('width')
                    ->numeric(),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lng')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('real_1')
                    ->numeric(),
                Forms\Components\TextInput::make('real_2')
                    ->numeric(),
                Forms\Components\TextInput::make('real_3')
                    ->numeric(),
                Forms\Components\TextInput::make('real_4')
                    ->numeric(),
                Forms\Components\TextInput::make('real_5')
                    ->numeric(),
                Forms\Components\TextInput::make('real_6')
                    ->numeric(),
                Forms\Components\TextInput::make('real_7')
                    ->numeric(),
                Forms\Components\TextInput::make('real_8')
                    ->numeric(),
                Forms\Components\TextInput::make('photo_0')
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_50')
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_100')
                    ->maxLength(255),
                Forms\Components\TextInput::make('photo_pho')
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->columnSpanFull(),
                Forms\Components\Textarea::make('note_pho')
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('team'),
                Forms\Components\TextInput::make('construct_type')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('spending_type')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('contractor.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('consultant.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('length')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('width')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('lat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('real_1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_4')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_5')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_6')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_7')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('real_8')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('photo_0')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_50')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_100')
                    ->searchable(),
                Tables\Columns\TextColumn::make('photo_pho')
                    ->searchable(),
                Tables\Columns\TextColumn::make('construct_type')
                    ->searchable(),
                Tables\Columns\TextColumn::make('spending_type')
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
            'index' => Pages\ListFacilities::route('/'),
            'create' => Pages\CreateFacility::route('/create'),
            'edit' => Pages\EditFacility::route('/{record}/edit'),
        ];
    }
}
