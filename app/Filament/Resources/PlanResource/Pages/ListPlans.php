<?php

namespace App\Filament\Resources\PlanResource\Pages;

use App\Filament\Resources\PlanResource;
use App\Filament\Imports\PlanImporter;
use App\Filament\Exports\PlanExporter;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\CheckboxList;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;

class ListPlans extends ListRecords
{
    protected static string $resource = PlanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\Action::make('export')
                ->label('Ekspor ke Excel')
                ->icon('heroicon-o-table-cells')
                ->color('success')
                ->form([
                    CheckboxList::make('selectedColumns')
                        ->label('Pilih Kolom yang ingin diekspor')
                        ->options(function () {
                            return collect(PlanExporter::getColumns())->mapWithKeys(function ($column) {
                                return [$column->getName() => $column->getLabel()];
                            })->toArray();
                        })
                        ->default(function () {
                            return collect(PlanExporter::getColumns())
                                ->map(fn ($column) => $column->getName())
                                ->toArray();
                        })
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        $filters = $this->getTableFilters();
                        $selectedColumns = $data['selectedColumns'];

                        return Excel::download(
                            new PlanExporter($filters, $selectedColumns),
                            'data_perencanaan_' . now()->format('Y-m-d_His') . '.xlsx'
                        );
                    } catch (\Exception $e) {
                        Notification::make()
                            ->danger()
                            ->title('Gagal mengeksport data')
                            ->body($e->getMessage())
                            ->send();
                    }
                }),
            Actions\Action::make('import')
                ->label('Import Data Excel')
                ->icon('heroicon-o-cloud-arrow-up')
                ->color('warning')
                ->form([
                    FileUpload::make('file')
                        ->label('Pilih File')
                        ->acceptedFileTypes([
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                            '.xlsx',
                            '.xls'
                        ])
                        ->maxSize(5120)
                        ->required()
                        ->disk('local')
                        ->directory('temp-imports')
                        ->visibility('private')
                        ->helperText('Format file harus .xlsx dengan ukuran maksimal 5MB')
                ])
                ->action(function (array $data) {
                    try {
                        if (isset($data['file'])) {
                            $filePath = storage_path('app/' . $data['file']);

                            // Debug information
                            Log::info('Plan file path:', [
                                'storage_path' => $filePath,
                                'exists' => file_exists($filePath),
                                'readable' => is_readable($filePath),
                                'file_size' => file_exists($filePath) ? filesize($filePath) : 0,
                                'mime_type' => file_exists($filePath) ? mime_content_type($filePath) : 'unknown',
                            ]);

                            if (!file_exists($filePath)) {
                                throw new \Exception('File tidak ditemukan di: ' . $filePath);
                            }

                            if (!is_readable($filePath)) {
                                throw new \Exception('File tidak dapat dibaca di: ' . $filePath);
                            }

                            // Log before importing the file
                            Log::info('Starting plan import process for file:', ['file_path' => $filePath]);

                            // Import the file
                            Excel::import(new PlanImporter(), $filePath);

                            // Notification for success
                            Notification::make()
                                ->success()
                                ->title('Data perencanaan berhasil diimport!')
                                ->send();
                        } else {
                            throw new \Exception('File tidak ditemukan dalam request');
                        }
                    } catch (\Exception $e) {
                        // Log the error message and stack trace
                        Log::error('Plan import error: ' . $e->getMessage());
                        Log::error('Stack trace: ' . $e->getTraceAsString());

                        Notification::make()
                            ->danger()
                            ->title('Gagal import data')
                            ->body($e->getMessage())
                            ->send();
                    }
                })
                ->requiresConfirmation()
                ->modalHeading('Import Data Perencanaan')
                ->modalDescription('Pastikan format file sesuai dengan template yang telah ditentukan. Harap gunakan template import terlebih dahulu supaya berhasil.')
                ->modalSubmitActionLabel('Upload dan Import'),

            Actions\Action::make('downloadTemplate')
                ->label('Download Template')
                ->icon('heroicon-o-cloud-arrow-down')
                ->color('danger')
                ->url('/data/template_plan.xlsx')
                ->openUrlInNewTab(true),
        ];
    }
}
