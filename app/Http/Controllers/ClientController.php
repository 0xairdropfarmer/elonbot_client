<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use EnvEditor;
use Illuminate\Http\Request;
use Binance\API as BinanceAPI;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Notifications\OrderNotify;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->ip_address  = trim(shell_exec("dig +short myip.opendns.com @resolver1.opendns.com"));
    }
   public function index()
    {
        $ApiKey = ApiKey::orderBy('created_at','desc')->first();
        // dd($ApiKey->BINANCE_API_KEY);
        return view('dashboard',compact('ApiKey'));
    }
    public function saveBinanceKey(Request $request){


        $binance_test = new BinanceAPI($request->BINANCE_API_KEY,$request->BINANCE_API_SECRET);

       try {
        $result = $binance_test->marketBuyTest("BTCUSDT",1);

    } catch (\Exception $exception) {
        return back()->withError($exception->getMessage())->withInput();
    }
     $ApiKey = ApiKey::first();
     $ApiKey->BINANCE_API_KEY = $request->BINANCE_API_KEY;
     $ApiKey->BINANCE_API_SECRET = $request->BINANCE_API_SECRET;
     $ApiKey->save();

         $request->session()->flash('success', 'Setup Binance API KEY success');
        return redirect()->route('dashboard');
    }
     public function adjustOrder(Request $request)
    {
        $ApiKey = ApiKey::first();
        $ApiKey->buydoge = $request->buydoge;
        $ApiKey->buybtc = $request->buybtc;
        $ApiKey->doge_order_size = $request->doge_order_size;
        $ApiKey->btc_order_size = $request->btc_order_size;
        $ApiKey->save();

         $request->session()->flash('success', 'Adjust order success');
          return redirect()->route('dashboard');
    }
     public function setStatus(Request $request)
    {

         if($request->get('botstatus') == 'start'){
           $request->session()->flash('success', 'bot already started');
         }else{

            $request->session()->flash('danger', 'bot already stop');
         }
         $ApiKey = ApiKey::first();
         $ApiKey->botstatus = $request->botstatus;
         $ApiKey->save();
           return redirect()->route('dashboard');
    }
      public function reciveBuySignal(Request $request)
    {
        $pathToPublicKey = storage_path().'/key.pub';
        $publicKey = \Spatie\Crypto\Rsa\PublicKey::fromFile($pathToPublicKey);

        $verify = $publicKey->verify('my message', $request->get('signature'));

         if($verify){
      if(env('botstatus')){
            if(env('buybtc') && $request->has('buybtc')){
                    $ApiKey = ApiKey::orderBy('created_at','desc')->first();
                    //  $binance = new BinanceAPI(env('BINANCE_API_KEY'),env('BINANCE_API_SECRET'));
                $binance = new BinanceAPI($ApiKey->BINANCE_API_KEY,$ApiKey->BINANCE_API_SECRET);

   try {
         $order = $binance->marketBuy("BNBBUSD",1);

                $orderHistory = new OrderHistory;
                $orderHistory->symbol = $order['symbol'];
                $orderHistory->orderId = $order['orderId'];
                $orderHistory->status = $order['status'];
                $orderHistory->save();
                $orderHistory->notify(new OrderNotify($orderHistory));
    $result = Http::post(env('command_center_address').'/keep_order_history',[

            'ip_address'=> $this->ip_address,
             'symbol'=>$order['symbol'],
             'orderId'=>$order['orderId'],
              'status'=>$order['status'],
         ]);

        info($result);
                 } catch (\Exception $exception) {

                      $result = Http::post(env('command_center_address').'/keep_order_history',[

            'ip_address'=> $this->ip_address,
             'error_message'=>$exception->getMessage(),
         ]);
            info($result);
            }
            }
            if(env('buydoge') && $request->has('buydoge')){
                 $ApiKey = ApiKey::orderBy('created_at','desc')->first();
                //  $binance = new BinanceAPI(env('BINANCE_API_KEY'),env('BINANCE_API_SECRET'));
                $binance = new BinanceAPI($ApiKey->BINANCE_API_KEY,$ApiKey->BINANCE_API_SECRET);

             try {
                $order = $binance->marketBuy("DOGEUSDT", env('doge_order_size'));
                $orderHistory = new OrderHistory;
                $orderHistory->symbol = $order['symbol'];
                $orderHistory->orderId = $order['orderId'];
                $orderHistory->status = $order['status'];
                $orderHistory->save();
                $orderHistory->notify(new OrderNotify($orderHistory));
                $result = Http::post(env('command_center_address').'/keep_order_history',[
                    'ip_address'=> $this->ip_address,
                    'symbol'=>$order['symbol'],
                    'orderId'=>$order['orderId'],
                    'status'=>$order['status'],
                ]);
                } catch (\Exception $exception) {
                        $result = Http::post(env('command_center_address').'/keep_order_history',[

                            'ip_address'=>$this->ip_address,
                        'error_message'=>$exception->getMessage(),
                    ]);

                }
            }


        }
    }else{
     return abort(403);
    }

 }




}
