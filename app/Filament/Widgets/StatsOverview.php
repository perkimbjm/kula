<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Facility;
use App\Models\Survey;
use App\Models\Work;
use App\Models\Track;
use App\Models\TicketFeedback;
use App\Models\TicketResponse;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
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
        return Auth::check() && in_array(Auth::user()->role_id, static::$allowedRoles);
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

        // Statistik baru
        $surveyCount = Survey::count();
        $currentYear = now()->year;
        $totalWorkCount = Work::whereYear('created_at', $currentYear)->count();

        // Perhitungan berdasarkan tabel Track untuk tahun ini
        $contractedCount = Track::where('kontrak', true)->whereYear('created_at', $currentYear)->count();
        $completedCount = Track::where('selesai', true)->whereYear('created_at', $currentYear)->count();
        $phoCount = Track::where('pho', true)->whereYear('created_at', $currentYear)->count();
        $paymentProgressCount = Track::where('keuangan', true)->whereYear('created_at', $currentYear)->count();
        $completedPackageCount = Track::where('bank', true)->where('laporan', true)->whereYear('created_at', $currentYear)->count();

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
            Stat::make('Total Survey Usulan', $surveyCount)
                ->description('Jumlah survey usulan terdaftar')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary'),
            Stat::make('Total Berkontrak', $contractedCount)
                ->description('Jumlah paket sudah berkontrak tahun ' . $currentYear . ' dari ' . $totalWorkCount . ' paket')
                ->descriptionIcon('heroicon-m-document-text')
                ->color('emerald'),
            Stat::make('Total Fisik Selesai', $completedCount)
                ->description('Jumlah Proses fisik selesai tahun ' . $currentYear)
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('violet'),
            Stat::make('Total PHO', $phoCount)
                ->description('Jumlah PHO Selesai tahun ' . $currentYear)
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('indigo'),
            Stat::make('Total Progres Pembayaran', $paymentProgressCount)
                ->description('Jumlah paket dalam progres pembayaran tahun ' . $currentYear)
                ->descriptionIcon('heroicon-m-currency-dollar')
                ->color('amber'),
            Stat::make('Total Paket Selesai', $completedPackageCount)
                ->description('Jumlah paket selesai tahun ' . $currentYear . ' dari ' . $totalWorkCount . ' paket')
                ->descriptionIcon('heroicon-m-flag')
                ->color('green'),
        ];
    }
}
