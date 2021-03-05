<?php

namespace App\Http\Controllers;
use EnvEditor;
use Illuminate\Http\Request;
use Binance\API as BinanceAPI;
class ClientController extends Controller
{
   public function index()
    {

        return view('dashboard');
    }
    public function saveBinanceKey(Request $request){
        EnvEditor::editKey('BINANCE_API_KEY',$request->BINANCE_API_KEY);
        EnvEditor::editKey('BINANCE_API_SECRET',$request->BINANCE_API_SECRET);
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
           $api = new BinanceAPI(env('BINANCE_API_KEY'),env('BINANCE_API_SECRET'));

        if(env('botstatus')){
            if(env('buybtc') && $request->has('buybtc')){

                $order = $api->marketBuy("BTCUSDT", env('btc_order_size'));
                 info($order);
            }
            if(env('buydoge') && $request->has('buydoge')){

                $order = $api->marketBuy("DOGEUSDT", env('doge_order_size'));
                info($order);
            }


        }
            // checking bot status
            // checking order status and quantity
 // send order to exchange
 // store result to db;
    }
}
