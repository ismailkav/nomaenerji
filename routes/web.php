<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\TeklifController;
use App\Http\Controllers\DefinitionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('firmalar', FirmController::class)
        ->parameters(['firmalar' => 'firm'])
        ->names('firms');

    Route::resource('urunler', ProductController::class)
        ->parameters(['urunler' => 'product'])
        ->names('products');

    Route::get('siparisler/planlama', [SiparisController::class, 'planning'])
        ->name('orders.planning');

    Route::post('siparisler/planlama/siparis-olustur', [SiparisController::class, 'planningCreatePurchase'])
        ->name('orders.planning.create-purchase');

    Route::patch('siparisler/{siparis}/planlama', [SiparisController::class, 'updatePlanning'])
        ->name('orders.planning.update');

    Route::resource('siparisler', SiparisController::class)
        ->parameters(['siparisler' => 'siparis'])
        ->names('orders');

    Route::resource('teklifler', TeklifController::class)
        ->parameters(['teklifler' => 'teklif'])
        ->names('offers');

    Route::post('teklifler/{teklif}/revize', [TeklifController::class, 'revize'])
        ->name('offers.revize');

    Route::delete('teklifler/{teklif}/revize', [TeklifController::class, 'revizeDestroy'])
        ->name('offers.revize.destroy');

    Route::get('teklifler/{teklif}/print', [TeklifController::class, 'print'])
        ->name('offers.print');
    Route::get('teklifler/{teklif}/pdf', [TeklifController::class, 'pdf'])
        ->name('offers.pdf');

    Route::post('teklifler/{teklif}/siparis-olustur', [TeklifController::class, 'createSalesOrder'])
        ->name('offers.create-sales-order');

    Route::get('teklifler/teklif-no/{teklifNo}', [TeklifController::class, 'redirectByTeklifNo'])
        ->where('teklifNo', '[^/]+')
        ->name('offers.by-no');

    Route::get('cari-gruplar', [DefinitionController::class, 'cariGroups'])
        ->name('definitions.cari-groups');
    Route::post('cari-gruplar', [DefinitionController::class, 'saveCariGroups'])
        ->name('definitions.cari-groups.save');

    Route::get('urun-gruplar', [DefinitionController::class, 'productGroups'])
        ->name('definitions.product-groups');
    Route::post('urun-gruplar', [DefinitionController::class, 'saveProductGroups'])
        ->name('definitions.product-groups.save');

    Route::get('islem-turleri', [DefinitionController::class, 'islemTurleri'])
        ->name('definitions.islem-turleri');
    Route::post('islem-turleri', [DefinitionController::class, 'saveIslemTurleri'])
        ->name('definitions.islem-turleri.save');

    Route::get('projeler', [DefinitionController::class, 'projects'])
        ->name('definitions.projects');
    Route::post('projeler', [DefinitionController::class, 'saveProjects'])
        ->name('definitions.projects.save');
});
