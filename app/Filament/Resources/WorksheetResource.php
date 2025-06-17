<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WorksheetResource\Pages;
use App\Models\Worksheet;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class WorksheetResource extends Resource
{
    protected static ?string $model = Worksheet::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Link Kertas Kerja';
    protected static ?string $navigationGroup = 'Manajemen Konten';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('menu')->required(),
                Forms\Components\TextInput::make('url')->required(),
                Forms\Components\Select::make('icon')
                    ->options([
                        'heroicon-o-academic-cap' => 'academic-cap',
                        'heroicon-o-archive-box-arrow-down' => 'archive-box-arrow-down',
                        'heroicon-o-archive-box-x-mark' => 'archive-box-x-mark',
                        'heroicon-o-archive-box' => 'archive-box',
                        'heroicon-o-banknotes' => 'banknotes',
                        'heroicon-o-book-open' => 'book-open',
                        'heroicon-o-bookmark-slash' => 'bookmark-slash',
                        'heroicon-o-bookmark-square' => 'bookmark-square',
                        'heroicon-o-bookmark' => 'bookmark',
                        'heroicon-o-briefcase' => 'briefcase',
                        'heroicon-o-bug-ant' => 'bug-ant',
                        'heroicon-o-building-library' => 'building-library',
                        'heroicon-o-building-office-2' => 'building-office-2',
                        'heroicon-o-building-office' => 'building-office',
                        'heroicon-o-building-storefront' => 'building-storefront',
                        'heroicon-o-cake' => 'cake',
                        'heroicon-o-calculator' => 'calculator',
                        'heroicon-o-calendar-date-range' => 'calendar-date-range',
                        'heroicon-o-calendar-days' => 'calendar-days',
                        'heroicon-o-calendar' => 'calendar',
                        'heroicon-o-camera' => 'camera',
                        'heroicon-o-chart-bar-square' => 'chart-bar-square',
                        'heroicon-o-chart-bar' => 'chart-bar',
                        'heroicon-o-document-text' => 'document-text',
                        'heroicon-o-dollar-sign' => 'dollar-sign',
                        'heroicon-o-document-duplicate' => 'document-duplicate',
                        'heroicon-o-check-circle' => 'check-circle',
                        'heroicon-o-calculator' => 'calculator',
                        'heroicon-o-clipboard-document-list' => 'clipboard-document-list',
                        'heroicon-o-clipboard-document' => 'clipboard-document',
                        'heroicon-o-clipboard-list' => 'clipboard-list',
                        'heroicon-o-clipboard' => 'clipboard',
                        'heroicon-o-clock' => 'clock',
                        'heroicon-o-user' => 'user',
                    ])
                    ->searchable()
                    ->required()
                    ->reactive(),
                Forms\Components\View::make('components.select-heroicon-preview')
                    ->visible(fn ($get) => filled($get('icon'))),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('menu')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('url')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('icon')
                    ->label('Icon')
                    ->formatStateUsing(fn ($state) => $state ? view('components.heroicon', ['icon' => $state])->render() : '')
                    ->html()
                    ->searchable(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWorksheets::route('/'),
            'create' => Pages\CreateWorksheet::route('/create'),
            'edit' => Pages\EditWorksheet::route('/{record}/edit'),
        ];
    }
}
