<?php
use Illuminate\Support\Facades\Route;
use Leazycms\EArsip\Controllers\AdminController;
use Leazycms\EArsip\Controllers\SuratMasukController;
use Leazycms\EArsip\Controllers\PejabatController;
$contexts = [
    [
        'domain' => parse_url(config('app.url'), PHP_URL_HOST),
        'prefix' => admin_path().'/earsip',
        'name'   => 'panel.earsip.',
    ],
    [
        'domain' => parse_url(config('earsip.url'), PHP_URL_HOST),
        'prefix' => null,
        'name'   => null,
    ],
];

foreach ($contexts as $ctx) {
    Route::group([
        'domain' => $ctx['domain'],
        'prefix' => $ctx['prefix'],
        'as'     => $ctx['name'],
    ], function () {
        Route::get('/', [AdminController::class, 'index']);
        Route::get('dashboard', [AdminController::class, 'index'])->name('earsip.dashboard');
        Route::resource('surat-masuk', SuratMasukController::class);
        Route::post('surat-masuk/datatable', [SuratMasukController::class, 'datatable'])->name('surat-masuk.datatable');
        Route::put('surat-masuk/{arsip}/disposisi', [SuratMasukController::class, 'disposisi'])->name('surat-masuk.disposisi');
        Route::get('riwayat', [AdminController::class, 'index'])->name('riwayat.index');

        Route::resource('pejabat', PejabatController::class);
        Route::post('pejabat/datatable', [PejabatController::class, 'datatable'])->name('pejabat.datatable');


    });
}
