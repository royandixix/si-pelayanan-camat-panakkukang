<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

\Illuminate\Support\Facades\Route::prefix(
    'pimpinan/pratinjau-laporan',
)
    ->name('pimpinan.laporan.')
    ->controller(
        \App\Http\Controllers\Pimpinan\ServiceReportPreviewController::class,
    )
    ->group(function (): void {
        \Illuminate\Support\Facades\Route::get(
            '/csv',
            'previewCsv',
        )->name('preview.csv');

        \Illuminate\Support\Facades\Route::get(
            '/pdf',
            'previewPdf',
        )->name('preview.pdf');

        \Illuminate\Support\Facades\Route::get(
            '/download/csv',
            'downloadCsv',
        )->name('download.csv');

        \Illuminate\Support\Facades\Route::get(
            '/download/pdf',
            'downloadPdf',
        )->name('download.pdf');
    });
