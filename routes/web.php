<?php

use App\Models\Work;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use App\Filament\Exports\FilteredWorkExport;
use App\Http\Controllers\InformationController;

Route::get('/', [InformationController::class, 'index'])->name('home');

Route::get('/map', function () {
    return view('map');
})->name('map');


Route::get('/guide', function () {
    return view('guide');
})->name('guide');


Route::get('/app/works/export', function () {
    $filters = request()->get('tableFilters', []);
    $export = new FilteredWorkExport($filters);

    return Excel::download($export, 'filtered_work_export.xlsx');
})->name('works.export');

Route::get('app/facilities/export', function () {
    $filters = request()->get('tableFilters', []);
    $export = new FacilityExporter($filters);

    return Excel::download($export, 'Data PSU.xlsx');
})->name('facilities.export');