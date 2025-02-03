<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Facility;
use App\Models\TicketFeedback;
use App\Models\TicketResponse;
use Illuminate\Support\Carbon;
use Filament\Support\Enums\IconPosition;
use Filament\Widgets\StatsOverviewWidget\Stat;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget

{
    use HasWidgetShield;

    protected static array $allowedRoles = [1];

    public static function canView(): bool
    {
        return auth()->check() && in_array(auth()->user()->role_id, static::$allowedRoles);
    }

    
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        
        $userCount = User::count();
        $complaintCount = Ticket::count();
        $responseCount = TicketResponse::count();
        // Fetch average rating from TicketFeedback table.  Handle potential errors.
        $ratingAverage = TicketFeedback::avg('rating');
        $ratingAverage = (float) ($ratingAverage ?: 0);
        $starRating = str_repeat('★', floor($ratingAverage)) . str_repeat('☆', 5 - floor($ratingAverage));
        $satisfactionText = "Tingkat Kepuasan " . number_format($ratingAverage, 1) . "/5.0";

        return [
            Stat::make('Total Pengguna', $userCount)
                ->description('Jumlah pengguna terdaftar')
                ->descriptionIcon('heroicon-m-users')
                ->color('info'),
            Stat::make('Total Pengaduan', $complaintCount)
                ->description('Jumlah pengaduan masuk')
                ->descriptionIcon('heroicon-m-chat-bubble-left-ellipsis')
                ->color('danger'),
            Stat::make('Total Respon', $responseCount)
                ->description('Jumlah pengaduan yang ditanggapi')
                ->descriptionIcon('heroicon-m-chat-bubble-left-right')
                ->color('success'),
                // ->extraAttributes([
                //     'class' => 'cursor-pointer',
                //     'wire:click' => "\$dispatch('setStatusFilter', { filter: 'processed' })",
                // ]),
            Stat::make('Kepuasan Pengguna', $starRating)
                ->description($satisfactionText)
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
