    <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>

    </x-slot>
@if($errors->any())


                    <div class="px-5 py-3 mb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded-md" role="alert">
                      @foreach ($errors->all() as $message)
                       <b> error => </b> {{ $message }}
                      @endforeach
                    </div>

@endif

@if (\Session::has('success'))
   <div class="px-5 py-3 mb-4 text-sm text-green-900 bg-green-100 border border-green-200 rounded-md" role="alert">
                   {!! \Session::get('success') !!}
    </div>
@endif
@if (\Session::has('danger'))
   <div class="px-5 py-3 mb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded-md" role="alert">
                   {!! \Session::get('danger') !!}
    </div>
@endif
<div class="grid grid-cols-3 gap-4">
<div class="leading-loose">
    <form class="max-w-xl p-10 m-4 bg-white rounded shadow-xl" action="{{route('savebinancekey')}}" method="post" >
        @csrf
        <p class="font-medium text-gray-800">2.Set Binance API Key</p>
        <div class="mt-4">
         <label class="block text-sm text-gray-00" for="BINANCE_API_KEY">BINANCE_API_KEY</label>
         <input value="{{DotenvEditor::getValue('BINANCE_API_KEY')}}" class="w-full px-5 py-4 text-gray-700 bg-gray-200 rounded" id="BINANCE_API_KEY" name="BINANCE_API_KEY" type="text" required="" placeholder="Your BINANCE_API_KEY" aria-label="Name">
       </div>
    <div class="mt-4">
         <label class="block text-sm text-gray-00" for="BINANCE_API_SECRET">BINANCE_API_SECRET</label>
         <input value="{{DotenvEditor::getValue('BINANCE_API_SECRET')}}" class="w-full px-5 py-4 text-gray-700 bg-gray-200 rounded" id="BINANCE_API_SECRET" name="BINANCE_API_SECRET" type="text" required="" placeholder="Your BINANCE_API_SECRET" aria-label="Name">
       </div>
       <div class="mt-4">
         <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">Save</button>
       </div>
     </form>

</div>
<div class="leading-loose">
<form class="max-w-xl p-10 m-4 bg-white rounded shadow-xl" action="{{route('adjustorder')}}" method="post" >
    @csrf
    <p class="font-medium text-gray-800">3.Adjust Order ( buy with market price )</p>
    <div class="mt-4">
      <label class="block text-sm text-gray-00" for="buydoge">Buy Doge Coin</label>
       <div class="mt-2">
    <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ (DotenvEditor::getValue('buydoge')=="yes")? "checked" : "" }} name="buydoge" value="yes">
      <span class="ml-2">Yes</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" name="buydoge" {{ (DotenvEditor::getValue('buydoge')=="nope")? "checked" : "" }} value="nope">
      <span class="ml-2">Nope</span>
    </label>
  </div>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" value="{{DotenvEditor::getValue('doge_order_size')}}" id="doge_order_size" name="doge_order_size" type="number" required="" placeholder="Quantity that your want to buy?" aria-label="Name">
    </div>
    <div class="mt-4">
     <label class="block text-sm text-gray-00" for="buybtc">Buy BitCoin</label>
     <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ (DotenvEditor::getValue('buybtc') == "yes")? "checked" : "" }} name="buybtc"  value="yes">
      <span class="ml-2">Yes</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" name="buybtc" {{ (DotenvEditor::getValue('buybtc') =="nope")? "checked" : "" }} value="nope">
      <span class="ml-2">Nope</span>
    </label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" value="{{DotenvEditor::getValue('btc_order_size')}}" id="btc_order_size" name="btc_order_size" type="text" required="" placeholder="Quantity that your want to buy?" aria-label="Name">
    </div>
    <div class="mt-4">
      <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">Save</button>
    </div>
  </form>
</div>
 <div class="leading-loose">
  <form class="max-w-xl p-10 m-4 bg-white rounded shadow-xl" action="{{route('setstatus')}}" method="post" >
     @csrf
    <p class="font-medium text-gray-800">4.Start or stop bot</p>
  <div class="mt-4">
  <div class="mt-2">

    <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ (DotenvEditor::getValue('botstatus') == "start")? "checked" : "" }} name="status" value="start">
      <span class="ml-2">Start</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" {{ (DotenvEditor::getValue('botstatus') == "stop")? "checked" : "" }} name="status" value="stop">
      <span class="ml-2">Stop</span>
    </label>
  </div>
    <div class="mt-4">


      <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">Save</button>

    </div>
    <div class="mt-4">
        @if(DotenvEditor::getValue('botstatus') == "start")
    <div class="px-5 py-3 mb-4 text-sm text-green-900 bg-green-100 border border-green-200 rounded-md" role="alert">
      <p class="font-medium text-gray-800">Currently your bot are start</p>
    </div>
        @else
 <div class="px-5 py-3 mb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded-md" role="alert">
      <p class="font-medium text-gray-800">Currently your bot are Stop</p>
    </div>
        @endif
    </div>
</div>

  </form>
</div>
</div>

</x-app-layout>
