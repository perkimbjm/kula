<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Survey;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Dotswan\MapPicker\Fields\Map;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SurveyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SurveyResource\RelationManagers;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Tracking';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Paket')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('supervisor')
                    ->label('Pendamping')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('district_id')
                    ->label('Kecamatan')
                    ->relationship('district', 'name')
                    ->required(),
                Forms\Components\Select::make('village_id')
                    ->label('Kelurahan / Desa')
                    ->relationship('village', 'name')
                    ->required(),
                Forms\Components\TextInput::make('length')
                    ->label('Panjang (m)')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('type')
                    ->label('Jenis Usulan')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('program')
                    ->label('Jenis Paket')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),
                Forms\Components\Grid::make(2)
                    ->schema([
                    Forms\Components\TextInput::make('lat')
                            ->label('lat')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $set('location', [
                                        'lat' => $state,
                                        'lng' => $get('lng') ?? 0,
                                    ]);
                                }
                            }),
                    Forms\Components\TextInput::make('lng')
                            ->label('lng')
                            ->live(onBlur: true)
                            ->afterStateUpdated(function ($state, Set $set, Get $get) {
                                if ($state) {
                                    $set('location', [
                                        'lat' => $get('lat') ?? 0,
                                        'lng' => $state,
                                    ]);
                                }
                            }),
                    ]),
                Map::make('location')
                    ->label('Lokasi')
                    ->columnSpanFull()
                    ->defaultLocation(latitude: -2.3357594, longitude: 115.460096)
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, ?array $state): void {
                        $set('lat',  $state['lat']);
                        $set('lng', $state['lng']);
                    })
                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                        if ($record && isset($record->lat) && isset($record->lng)) {
                            $set('location', [
                                'lat' => $record->lat,
                                'lng' => $record->lng
                            ]);
                        }
                    })
                    ->extraStyles([
                        'min-height: 65vh',
                    ])
                    ->liveLocation(true, true, 120000)
                    ->showMarker()
                    ->markerColor("#22c55eff")
                    ->showFullscreenControl()
                    ->showZoomControl()
                    ->draggable()
                    ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png")
                    ->zoom(18)
                    ->detectRetina()
                    ->showMyLocationButton(),
                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('supervisor')
                    ->label('Pendamping')
                    ->searchable(),
                Tables\Columns\TextColumn::make('district.name')
                    ->label('Kecamatan')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('village.name')
                    ->label('Kelurahan / Desa')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('length')
                    ->label('Panjang (m)')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Jenis Usulan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('program')
                    ->label('Paket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lat')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lng')
                    ->searchable(),
                Tables\Columns\TextColumn::make('note')
                    ->label('Catatan'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSurveys::route('/'),
        ];
    }
}
