<?php

namespace App\Http\Controllers;

use App\Models\ApiKey;
use EnvEditor;
use Illuminate\Http\Request;
use Binance\API as BinanceAPI;
use App\Models\OrderHistory;
use Illuminate\Support\Facades\Http;
use App\Models\User;
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
        $result = $binance_test->marketBuyTest("BTCUSDT",100);

    } catch (\Exception $exception) {
        return back()->withError($exception->getMessage())->withInput();
    }
     ApiKey::updateOrCreate(['BINANCE_API_KEY'=>$request->BINANCE_API_KEY,'BINANCE_API_SECRET'=>$request->BINANCE_API_SECRET]);
         $request->session()->flash('success', 'Setup Binance API KEY success');
        return redirect()->route('dashboard');
    }
     public function adjustOrder(Request $request)
    {

         EnvEditor::editKey('buydoge',$request->buydoge);
         EnvEditor::editKey('buybtc',$request->buybtc);
         EnvEditor::editKey('doge_order_size',$request->doge_order_size);
         EnvEditor::editKey('btc_order_size',$request->btc_order_size);
         $request->session()->flash('success', 'Adjust order success');
          return redirect()->route('dashboard');
    }
     public function setStatus(Request $request)
    {

         if($request->get('status') == 'start'){
           $request->session()->flash('success', 'bot already started');
         }else{

            $request->session()->flash('danger', 'bot already stop');
         }
         EnvEditor::editKey('botstatus',$request->status);

           return redirect()->route('dashboard');
    }
      public function reciveBuySignal(Request $request)
    {


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
    }
}
