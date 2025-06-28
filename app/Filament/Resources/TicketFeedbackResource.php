<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Ticket;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\TicketFeedback;
use App\Models\TicketResponse;
use Illuminate\Support\Carbon;
use Filament\Resources\Resource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\TicketFeedbackResource\Pages;
use App\Filament\Resources\TicketFeedbackResource\RelationManagers;
use IbrahimBougaoua\FilamentRatingStar\Columns\Components\RatingStar;
use IbrahimBougaoua\FilamentRatingStar\Forms\Components\RatingStar as RatingStarForm;
use Closure;
use Illuminate\Support\Facades\Gate;

class TicketFeedbackResource extends Resource
{
    protected static ?string $model = TicketFeedback::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-left';

    protected static ?string $navigationGroup = 'Manajemen Usulan / Pengaduan';

    protected static ?string $label = 'Tanggapan dari Respon';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('ticket_id')
                    ->label('Nomor Tiket')
                    ->options(function (callable $get, $record) {
                        $query = Ticket::query();

                        $user = Auth::user();

                        // Check if the user's role_id is 2 (Warga)
                        if ($user && $user->role_id == 2) {
                            $query->where('user_id', $user->id)
                                  ->whereHas('ticketResponses'); // Hanya ticket yang sudah ada response
                        } elseif ($user && $user->role_id == 3) { // Admin
                            // Ambil ticket_id dari response yang dibuat oleh admin ini
                            $ticketIds = TicketResponse::where('admin_id', $user->id)
                                ->pluck('ticket_id');
                            $query->whereIn('id', $ticketIds);
                        }

                        if ($record) {
                            $query->where(function ($q) use ($record) {
                                $q->whereDoesntHave('feedback')
                                  ->orWhere('id', $record->ticket_id);
                            });
                        } else {
                            $query->whereDoesntHave('feedback');
                        }

                        return $query->get()
                        ->map(function ($ticket) {
                            return [
                                'value' => $ticket->id,
                                'label' => $ticket->ticket_number . ' (' . Carbon::parse($ticket->updated_at)->format('d M Y H:i') . ')'
                            ];
                        })
                        ->pluck('label', 'value')
                        ->toArray();
                    })
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $ticket = Ticket::with('ticketResponses')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $latestResponse = $ticket->ticketResponses()->latest()->first();
                                $set('response', $latestResponse ? $latestResponse->response : '');
                            } else {
                                $set('issue', '');
                                $set('response', '');
                            }
                        }
                    })
                    ->afterStateHydrated(function ($state, callable $set) {
                        if ($state) {
                            $ticket = Ticket::with('ticketResponses')->find($state);
                            if ($ticket) {
                                $set('issue', $ticket->issue);
                                $latestResponse = $ticket->ticketResponses()->latest()->first();
                                $set('response', $latestResponse ? $latestResponse->response : '');
                            }
                        }
                    }),
                Forms\Components\Hidden::make('user_id')
                    ->default(Auth::id())
                    ->required(),
                Forms\Components\TextInput::make('issue')
                    ->label('Masalah')
                    ->readonly(),
                Forms\Components\TextInput::make('response')
                    ->label('Tanggapan')
                    ->readonly(),
                Forms\Components\Textarea::make('feedback')
                    ->required()
                    ->columnSpanFull(),
                RatingStarForm::make('rating')
                    ->label('Rating'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->selectable()            ->query(function (Builder $query) {
                $user = Auth::user();
                $baseQuery = static::getModel()::query()->with(['ticket', 'user']);

                if ($user->role_id === 3) { // Admin
                    // Ambil ticket_id dari response yang dibuat oleh admin ini
                    $ticketIds = TicketResponse::where('admin_id', $user->id)
                        ->pluck('ticket_id');
                    return $baseQuery->whereIn('ticket_id', $ticketIds);
                } elseif ($user->role_id === 2) { // Warga
                    return $baseQuery->where('user_id', $user->id);
                }

                return $baseQuery;
            })
            ->columns([
                Tables\Columns\TextColumn::make('ticket.ticket_number')
                    ->label('Nomor Tiket')
                    ->searchable(),
                Tables\Columns\TextColumn::make('ticket.latestResponse')
                    ->label('Tanggapan Terakhir'),
                Tables\Columns\TextColumn::make('feedback')
                    ->label('Feedback')
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
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
            'index' => Pages\ManageTicketFeedback::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        $user = Auth::user();
        if ($user->role_id === 2) {
            return static::getModel()::where('user_id', $user->id)->count();
        }
        return static::getModel()::count();
    }

    public static function getTableRecordActionUsing(): ?Closure
    {
        return function (TicketFeedback $record): bool {
            $user = Auth::user();
            if ($user->role_id === 2) {
                return $user->id === $record->user_id;
            }
            return true;
        };
    }

    public static function canViewAny(): bool
    {
        $user = Auth::user();

        // Memanggil policy langsung tanpa Gate
        $policy = app(\App\Policies\TicketFeedbackPolicy::class);
        return $policy->viewAny($user);
    }
}
