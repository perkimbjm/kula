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
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\SurveyResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\SurveyResource\RelationManagers;
use Filament\Forms\Components\TextInput;

class SurveyResource extends Resource
{
    protected static ?string $model = Survey::class;

    protected static ?string $navigationIcon = 'heroicon-o-magnifying-glass-circle';

    protected static ?string $navigationGroup = 'Manajemen Proyek';

    protected static ?string $label = 'Survei';

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

                // Hidden fields untuk MapPicker (sesuai dokumentasi)
                TextInput::make('latitude')
                    ->hiddenLabel()
                    ->hidden(),
                TextInput::make('longitude')
                    ->hiddenLabel()
                    ->hidden(),

                TextInput::make('lat')
                    ->label('Latitude')
                    ->placeholder('Contoh: -6.200000')
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire): void {
                        $lng = $get('lng');
                        if ($state && $lng && is_numeric($state) && is_numeric($lng)) {
                            $set('location', ['lat' => (string)$state, 'lng' => (string)$lng]);
                            $set('latitude', (string)$state);  // Sync ke hidden field
                            $set('longitude', (string)$lng);   // Sync ke hidden field
                            $livewire->dispatch('refreshMap');
                        }
                    })
                    ->rules(['nullable', 'numeric', 'between:-90,90']),
                TextInput::make('lng')
                    ->label('Longitude')
                    ->placeholder('Contoh: 106.816666')
                    ->reactive()
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire): void {
                        $lat = $get('lat');
                        if ($state && $lat && is_numeric($state) && is_numeric($lat)) {
                            $set('location', ['lat' => (string)$lat, 'lng' => (string)$state]);
                            $set('latitude', (string)$lat);    // Sync ke hidden field
                            $set('longitude', (string)$state); // Sync ke hidden field
                            $livewire->dispatch('refreshMap');
                        }
                    })
                    ->rules(['nullable', 'numeric', 'between:-180,180']),
                Map::make('location')
                    ->label('Pilih Lokasi di Peta')
                    ->columnSpanFull()
                    ->defaultLocation(latitude: -2.33668, longitude: 115.46028)
                    ->afterStateUpdated(function (Set $set, ?array $state): void {
                        // Debug: Log state untuk troubleshooting
                        Log::info('SurveyResource MapPicker afterStateUpdated:', $state ?? []);

                        if ($state && isset($state['lat']) && isset($state['lng'])) {
                            // Cast ke string untuk konsistensi dengan database
                            $lat = (string) $state['lat'];
                            $lng = (string) $state['lng'];

                            // Set ke field yang visible
                            $set('lat', $lat);
                            $set('lng', $lng);

                            // Set ke hidden fields untuk MapPicker
                            $set('latitude', $lat);
                            $set('longitude', $lng);

                            // Debug: Log koordinat yang di-set
                            Log::info('SurveyResource Setting coordinates:', ['lat' => $lat, 'lng' => $lng]);
                        }
                    })
                    ->afterStateHydrated(function ($state, $record, Set $set): void {
                        if ($record && $record->lat && $record->lng) {
                            $set('location', [
                                'lat' => $record->lat,
                                'lng' => $record->lng
                            ]);
                            // Sync ke hidden fields juga
                            $set('latitude', $record->lat);
                            $set('longitude', $record->lng);
                        }
                    })
                    ->showMarker()
                    ->draggable(true)
                    ->clickable(true)
                    ->showMyLocationButton(true)
                    ->showZoomControl()
                    ->showFullscreenControl()
                    ->liveLocation(false, false, 5000)
                    ->zoom(15)
                    ->tilesUrl("https://tile.openstreetmap.de/{z}/{x}/{y}.png"),
                Forms\Components\Textarea::make('note')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()            ->columns([
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
