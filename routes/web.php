<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FirmController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SiparisController;
use App\Http\Controllers\TeklifController;
use App\Http\Controllers\DefinitionController;
use App\Http\Controllers\ConsignmentController;
use App\Http\Controllers\StockInventoryController;
use App\Http\Controllers\StockFicheController;
use App\Http\Controllers\ExchangeRateController;
use App\Http\Controllers\InvoiceController;
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

    Route::post('siparisler/planlama/revize', [SiparisController::class, 'planningSaveRevision'])
        ->name('orders.planning.save-revision');

    Route::get('siparisler/planlama/revize-depo', [SiparisController::class, 'planningRevisionDepotData'])
        ->name('orders.planning.revision-depot-data');

    Route::post('siparisler/planlama/revize-depo', [SiparisController::class, 'planningTransferRevision'])
        ->name('orders.planning.revision-depot-save');

    Route::get('siparisler/planlama/revize-listesi', [SiparisController::class, 'planningRevisionListData'])
        ->name('orders.planning.revision-list-data');

    Route::post('siparisler/planlama/revize-listesi', [SiparisController::class, 'planningRevisionListSave'])
        ->name('orders.planning.revision-list-save');

    Route::post('siparisler/planlama/revize-listesi/sil', [SiparisController::class, 'planningRevisionListDelete'])
        ->name('orders.planning.revision-list-delete');

    Route::patch('siparisler/{siparis}/planlama', [SiparisController::class, 'updatePlanning'])
        ->name('orders.planning.update');

    Route::resource('siparisler', SiparisController::class)
        ->parameters(['siparisler' => 'siparis'])
        ->names('orders');

    Route::resource('teklifler', TeklifController::class)
        ->parameters(['teklifler' => 'teklif'])
        ->names('offers');

    Route::get('faturalar/siparis-satirlari', [InvoiceController::class, 'orderLinesForTransfer'])
        ->name('invoices.order-lines');

    Route::delete('faturalar/{fatura}/satirlar/{detay}', [InvoiceController::class, 'destroyLine'])
        ->name('invoices.lines.destroy');

    Route::get('faturalar/{fatura}/satirlar/{detay}/eslestirmeler', [InvoiceController::class, 'lineLinks'])
        ->name('invoices.lines.links');

    Route::resource('faturalar', InvoiceController::class)
        ->parameters(['faturalar' => 'fatura'])
        ->names('invoices');

    Route::get('kur/today', [ExchangeRateController::class, 'today'])
        ->name('exchange-rate.today');

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

    Route::get('depolar', [DefinitionController::class, 'depots'])
        ->name('definitions.depots');
    Route::post('depolar', [DefinitionController::class, 'saveDepots'])
        ->name('definitions.depots.save');

    Route::prefix('stok')->group(function () {
        Route::get('konsinye-giris', [ConsignmentController::class, 'indexIn'])
            ->name('stock.consignment-in.index');
        Route::get('konsinye-giris/yeni', [ConsignmentController::class, 'newIn'])
            ->name('stock.consignment-in.create');
        Route::post('konsinye-giris', [ConsignmentController::class, 'storeIn'])
            ->name('stock.consignment-in.store');
        Route::get('konsinye-giris/{fiche}', [ConsignmentController::class, 'editIn'])
            ->name('stock.consignment-in.edit');
        Route::post('konsinye-giris/{fiche}', [ConsignmentController::class, 'updateIn'])
            ->name('stock.consignment-in.update');

        Route::get('konsinye-cikis', [ConsignmentController::class, 'indexOut'])
            ->name('stock.consignment-out.index');
        Route::get('konsinye-cikis/yeni', [ConsignmentController::class, 'newOut'])
            ->name('stock.consignment-out.create');
        Route::post('konsinye-cikis', [ConsignmentController::class, 'storeOut'])
            ->name('stock.consignment-out.store');
        Route::get('konsinye-cikis/{fiche}', [ConsignmentController::class, 'editOut'])
            ->name('stock.consignment-out.edit');
        Route::post('konsinye-cikis/{fiche}', [ConsignmentController::class, 'updateOut'])
            ->name('stock.consignment-out.update');

        Route::get('lookup-cari', [ConsignmentController::class, 'lookupCari'])
            ->name('stock.lookup-cari');
        Route::get('urun-ara', [ConsignmentController::class, 'searchProducts'])
            ->name('stock.products.search');

        Route::get('envanter', [StockInventoryController::class, 'index'])
            ->name('stock.inventory.index');

        Route::get('envanter/rezerve-detay', [StockInventoryController::class, 'reserveDetails'])
            ->name('stock.inventory.reserve-details');

        Route::get('sayim-giris', [StockFicheController::class, 'indexCountIn'])
            ->name('stock.count-in.index');
        Route::get('sayim-giris/yeni', [StockFicheController::class, 'createCountIn'])
            ->name('stock.count-in.create');
        Route::post('sayim-giris', [StockFicheController::class, 'storeCountIn'])
            ->name('stock.count-in.store');
        Route::get('sayim-giris/{fiche}', [StockFicheController::class, 'editCountIn'])
            ->name('stock.count-in.edit');
        Route::post('sayim-giris/{fiche}', [StockFicheController::class, 'updateCountIn'])
            ->name('stock.count-in.update');
        Route::delete('sayim-giris/{fiche}', [StockFicheController::class, 'destroyCountIn'])
            ->name('stock.count-in.destroy');

        Route::get('sayim-cikis', [StockFicheController::class, 'indexCountOut'])
            ->name('stock.count-out.index');
        Route::get('sayim-cikis/yeni', [StockFicheController::class, 'createCountOut'])
            ->name('stock.count-out.create');
        Route::post('sayim-cikis', [StockFicheController::class, 'storeCountOut'])
            ->name('stock.count-out.store');
        Route::get('sayim-cikis/{fiche}', [StockFicheController::class, 'editCountOut'])
            ->name('stock.count-out.edit');
        Route::post('sayim-cikis/{fiche}', [StockFicheController::class, 'updateCountOut'])
            ->name('stock.count-out.update');
        Route::delete('sayim-cikis/{fiche}', [StockFicheController::class, 'destroyCountOut'])
            ->name('stock.count-out.destroy');

        Route::get('depo-transfer', [StockFicheController::class, 'indexTransfer'])
            ->name('stock.depot-transfer.index');
        Route::get('depo-transfer/yeni', [StockFicheController::class, 'createTransfer'])
            ->name('stock.depot-transfer.create');
        Route::post('depo-transfer', [StockFicheController::class, 'storeTransfer'])
            ->name('stock.depot-transfer.store');
        Route::get('depo-transfer/{fiche}', [StockFicheController::class, 'editTransfer'])
            ->name('stock.depot-transfer.edit');
        Route::post('depo-transfer/{fiche}', [StockFicheController::class, 'updateTransfer'])
            ->name('stock.depot-transfer.update');
        Route::delete('depo-transfer/{fiche}', [StockFicheController::class, 'destroyTransfer'])
            ->name('stock.depot-transfer.destroy');
    });
});
