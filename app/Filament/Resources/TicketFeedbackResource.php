<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TicketFeedback;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketFeedbackResource\Pages;
use App\Filament\Resources\TicketFeedbackResource\RelationManagers;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar;

class TicketFeedbackResource extends Resource
{
    protected static ?string $model = TicketFeedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Manajemen Usulan / Pengaduan';

    protected static ?string $label = 'Tanggapan dari Respon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->relationship('ticket', 'id')
                    ->required(),
                Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->required(),
                Forms\Components\Textarea::make('feedback')
                    ->required()
                    ->columnSpanFull(),
                RatingStar::make('rating')
                    ->label('Rating'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket.id')
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->numeric()
                    ->sortable(),
                RatingStar::make('rating')
                    ->size('sm'),
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
            'index' => Pages\ManageTicketFeedback::route('/'),
        ];
    }
}
