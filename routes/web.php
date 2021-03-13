<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\OrderHistoryController;
use Binance\API as BinanceAPI;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('testbuy',function(){
$binance = new BinanceAPI(env('BINANCE_API_KEY'),env('BINANCE_API_SECRET'));
 $order = $binance->marketBuy("BNBUSDT", 1);
return $order;
});

    Route::post('recive_buy_signal', [ClientController::class, 'reciveBuySignal'])->name('recive_buy_signal');
Route::middleware(['auth'])->group(function () {
    Route::get('/', [ClientController::class,'index'])->name('dashboard');
    Route::post('savetbinancekey', [ClientController::class, 'saveBinanceKey'])->name('savebinancekey');
    Route::post('setstatus', [ClientController::class, 'setStatus'])->name('setstatus');
    Route::post('adjustorder', [ClientController::class, 'adjustOrder'])->name('adjustorder');
    Route::get('order_history', [OrderHistoryController::class, 'index'])->name('order_history');
});

