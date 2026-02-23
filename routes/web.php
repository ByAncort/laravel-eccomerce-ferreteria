<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DataFeedController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\StockController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', 'login');

Route::middleware(['auth:sanctum', 'verified'])->group(function () {

    // Route for the getting the data feed
    Route::get('/json-data-feed', [DataFeedController::class, 'getDataFeed'])->name('json_data_feed');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [CustomerController::class, 'index'])->name('index');
        Route::get('/create', [CustomerController::class, 'create'])->name('create');
        Route::post('/', [CustomerController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [CustomerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CustomerController::class, 'update'])->name('update');
        Route::delete('/{id}', [CustomerController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('vendors')->name('vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::post('/', [VendorController::class, 'store'])->name('store');
        Route::get('/create', [VendorController::class, 'create'])->name('create');
        Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy');
    });

Route::prefix('items')->name('items.')->group(function () {
    Route::get('/', [ItemsController::class, 'index'])->name('index');
    Route::get('/create', [ItemsController::class, 'create'])->name('create');
    Route::post('/', [ItemsController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [ItemsController::class, 'edit'])->name('edit');
    Route::put('/{id}', [ItemsController::class, 'update'])->name('update');
    Route::delete('/{id}', [ItemsController::class, 'destroy'])->name('destroy');
    
    // Otras rutas
    Route::get('/low-stock', [ItemsController::class, 'lowStock'])->name('low-stock');
    Route::get('/featured', [ItemsController::class, 'featured'])->name('featured');
});

Route::prefix('stock')->name('stock.')->group(function () {
    Route::get('/',                              [StockController::class, 'index'])->name('index');
    Route::post('/',                             [StockController::class, 'store'])->name('store');
    Route::get('/{itemId}/history',              [StockController::class, 'history'])->name('history');
    Route::post('/{itemId}/move',                [StockController::class, 'move'])->name('move');
    Route::delete('/movement/{movementId}',      [StockController::class, 'destroyMovement'])->name('movement.destroy');
});


    Route::get('/ecommerce/orders', [OrderController::class, 'index'])->name('orders');
    Route::get('/ecommerce/suppliers', [InvoiceController::class, 'index'])->name('suppliers');
    Route::get('/ecommerce/products', [InvoiceController::class, 'index'])->name('products');

    Route::get('/ecommerce/shop', function () {
        return view('pages/ecommerce/shop');
    })->name('shop');    
    Route::get('/ecommerce/shop-2', function () {
        return view('pages/ecommerce/shop-2');
    })->name('shop-2');     
    Route::get('/ecommerce/product', function () {
        return view('pages/ecommerce/product');
    })->name('product');
    Route::get('/ecommerce/cart', function () {
        return view('pages/ecommerce/cart');
    })->name('cart');    
    Route::get('/ecommerce/cart-2', function () {
        return view('pages/ecommerce/cart-2');
    })->name('cart-2');    
    Route::get('/ecommerce/cart-3', function () {
        return view('pages/ecommerce/cart-3');
    })->name('cart-3');    
    Route::get('/ecommerce/pay', function () {
        return view('pages/ecommerce/pay');
    })->name('pay');     
    Route::get('/campaigns', [CampaignController::class, 'index'])->name('campaigns');
    Route::get('/community/users-tabs', [MemberController::class, 'indexTabs'])->name('users-tabs');
    Route::get('/community/users-tiles', [MemberController::class, 'indexTiles'])->name('users-tiles');
    Route::get('/community/profile', function () {
        return view('pages/community/profile');
    })->name('profile');
    Route::get('/community/feed', function () {
        return view('pages/community/feed');
    })->name('feed');     
    Route::get('/community/forum', function () {
        return view('pages/community/forum');
    })->name('forum');
    Route::get('/community/forum-post', function () {
        return view('pages/community/forum-post');
    })->name('forum-post');    
    Route::get('/community/meetups', function () {
        return view('pages/community/meetups');
    })->name('meetups');    
    Route::get('/community/meetups-post', function () {
        return view('pages/community/meetups-post');
    })->name('meetups-post');    
    Route::get('/finance/cards', function () {
        return view('pages/finance/credit-cards');
    })->name('credit-cards');
    Route::get('/finance/transactions', [TransactionController::class, 'index01'])->name('transactions');
    Route::get('/finance/transaction-details', [TransactionController::class, 'index02'])->name('transaction-details');
    Route::get('/job/job-listing', [JobController::class, 'index'])->name('job-listing');
 
    Route::fallback(function() {
        return view('pages/utility/404');
    });    
});
