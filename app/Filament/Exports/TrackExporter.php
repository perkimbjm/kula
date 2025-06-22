<?php

namespace App\Filament\Exports;

use App\Models\Track;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class TrackExporter extends Exporter
{
    protected static ?string $model = Track::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('id')
                ->label('ID'),
            ExportColumn::make('work.name')
                ->label('Nama Paket'),
            ExportColumn::make('contract_number')
                ->label('No. Kontrak'),
            ExportColumn::make('consultant_name')
                ->label('Perencana'),
            ExportColumn::make('supervisor_name')
                ->label('Pengawas'),
            ExportColumn::make('contractor_name')
                ->label('Pelaksana'),
            ExportColumn::make('work_year')
                ->label('Tahun'),
            ExportColumn::make('survei')
                ->label('Survei')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('pemilihan')
                ->label('Pemilihan')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('kontrak')
                ->label('Kontrak')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('uang_muka')
                ->label('Uang Muka')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('kritis')
                ->label('Kritis')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('selesai')
                ->label('Selesai')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('pho')
                ->label('PHO')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('aset')
                ->label('Aset')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('ppk_dinas')
                ->label('PPK DINAS')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('bendahara')
                ->label('Bendahara')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('pengguna_anggaran')
                ->label('Pengguna Anggaran')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('keuangan')
                ->label('Keuangan')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('bank')
                ->label('BANK')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('laporan')
                ->label('Laporan')
                ->formatStateUsing(fn (bool $state): string => $state ? 'Ya' : 'Tidak'),
            ExportColumn::make('pemeriksa_string')
                ->label('Pemeriksa'),
            ExportColumn::make('latest_progress')
                ->label('Progress Terakhir'),
            ExportColumn::make('lat')
                ->label('Latitude'),
            ExportColumn::make('lng')
                ->label('Longitude'),
            ExportColumn::make('panjang')
                ->label('Panjang (M)'),
            ExportColumn::make('lebar')
                ->label('Lebar (M)'),
            ExportColumn::make('status')
                ->label('Status')
                ->formatStateUsing(function ($state): string {
                    if ($state instanceof \App\Enums\TrackStatus) {
                        return $state->getLabel();
                    }

                    if (is_string($state)) {
                        return match ($state) {
                            'baik' => 'Baik',
                            'dengan_perbaikan' => 'Dengan Perbaikan',
                            'kurang' => 'Kurang',
                            default => 'Tidak Diketahui',
                        };
                    }

                    return 'Tidak Diketahui';
                }),
            ExportColumn::make('catatan_tim_teknis')
                ->label('Catatan Tim Teknis'),
            ExportColumn::make('created_at')
                ->label('Dibuat'),
            ExportColumn::make('updated_at')
                ->label('Diperbarui'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Export tracking telah selesai dan ' . number_format($export->successful_rows) . ' ' . str('baris')->plural($export->successful_rows) . ' berhasil diekspor.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('baris')->plural($failedRowsCount) . ' gagal diekspor.';
        }

        return $body;
    }
}
