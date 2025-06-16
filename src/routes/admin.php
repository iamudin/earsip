<?php
use Illuminate\Support\Facades\Route;
use Leazycms\EArsip\Controllers\AdminController;
use Leazycms\EArsip\Controllers\SuratMasukController;
use Leazycms\EArsip\Controllers\PejabatController;

Route::group([
    'domain' => no_http_url(config('app.url')),
    'prefix' => admin_path().'/earsip',
    'as'   => 'panel.earsip.',
], function () {
    Route::resource('pejabat', PejabatController::class);
    Route::post('pejabat/datatable', [PejabatController::class, 'datatable'])->name('pejabat.datatable');
});
    Route::group([
        'domain' => no_http_url(config('earsip.url')),
    ], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('dashboard', [AdminController::class, 'index'])->name('earsip.dashboard');
        Route::get('surat-masuk/selesai', [SuratMasukController::class, 'index'])->name('surat-masuk.index.selesai');
        Route::resource('surat-masuk', SuratMasukController::class);
        Route::post('surat-masuk/datatable', [SuratMasukController::class, 'datatable'])->name('surat-masuk.datatable');
        Route::post('surat-masuk/riwayat', [SuratMasukController::class, 'riwayat'])->name('surat-masuk.riwayat');
        Route::put('surat-masuk/{arsip}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
        Route::get('surat-masuk/{arsip}/destroy', [SuratMasukController::class, 'destroy'])->name('surat-masuk.destroy');
        Route::post('mergepdf', [SuratMasukController::class, 'merge'])->name('merge.pdf');
    });
