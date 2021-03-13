    <x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Dashboard') }}
        </h2>

    </x-slot>
@if(session('error'))


                    <div class="px-5 py-3 mb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded-md" role="alert">
                     {{ session('error') }}
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
        <p class="font-medium text-gray-800">1.ตั้งค่า Binance API Key</p>
        <div class="mt-4">
         <label class="block text-sm text-gray-00" for="BINANCE_API_KEY">BINANCE_API_KEY</label>
         <input value="{{$ApiKey->BINANCE_API_KEY}}" class="w-full px-5 py-4 text-gray-700 bg-gray-200 rounded" id="BINANCE_API_KEY" name="BINANCE_API_KEY" type="text" required="" placeholder="Your BINANCE_API_KEY" aria-label="Name">
       </div>
    <div class="mt-4">
         <label class="block text-sm text-gray-00" for="BINANCE_API_SECRET">BINANCE_API_SECRET</label>
         <input value="{{$ApiKey->BINANCE_API_SECRET}}" class="w-full px-5 py-4 text-gray-700 bg-gray-200 rounded" id="BINANCE_API_SECRET" name="BINANCE_API_SECRET" type="text" required="" placeholder="Your BINANCE_API_SECRET" aria-label="Name">
       </div>
       <div class="mt-4">
         <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">บันทึก</button>
       </div>
     </form>

</div>
<div class="leading-loose">
<form class="max-w-xl p-10 m-4 bg-white rounded shadow-xl" action="{{route('adjustorder')}}" method="post" >
    @csrf
    <p class="font-medium text-gray-800">2.เลือกเหรียญที่จะซื้อ( ซื้อในราคาตลาดในตอนนั้นโดยใช้ USDT )</p>
    <div class="mt-4">
      <label class="block text-sm text-gray-00" for="buydoge">ซื้อ Doge Coin</label>
       <div class="mt-2">
    <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ ($ApiKey->buydoge =="yes")? "checked" : "" }} name="buydoge" value="yes">
      <span class="ml-2">โอเค</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" name="buydoge" {{ ($ApiKey->buydoge =="nope")? "checked" : "" }} value="nope">
      <span class="ml-2">ไม่</span>
    </label>
  </div>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" value="{{$ApiKey->doge_order_size }}" id="doge_order_size" name="doge_order_size" type="number" required="" placeholder="ขั้นต่ำ 1000" aria-label="Name">
    </div>
    <div class="mt-4">
     <label class="block text-sm text-gray-00" for="buybtc">ซื้อ BitCoin</label>
     <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ ($ApiKey->buybtc == "yes") ? "checked" : "" }} name="buybtc"  value="yes">
      <span class="ml-2">โอเค</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" name="buybtc" {{ ($ApiKey->buybtc =="nope")? "checked" : "" }} value="nope">
      <span class="ml-2">ไม่</span>
    </label>
      <input class="w-full px-5 py-1 text-gray-700 bg-gray-200 rounded" value="{{$ApiKey->btc_order_size}}" id="btc_order_size" name="btc_order_size" type="text" required="" placeholder="Quantity that your want to buy?" aria-label="Name">
    </div>
    <div class="mt-4">
      <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">บันทึก</button>
    </div>
  </form>
</div>
 <div class="leading-loose">
  <form class="max-w-xl p-10 m-4 bg-white rounded shadow-xl" action="{{route('setstatus')}}" method="post" >
     @csrf
    <p class="font-medium text-gray-800">3.เปิดหรือปิดบอท</p>
  <div class="mt-4">
  <div class="mt-2">

    <label class="inline-flex items-center">
      <input type="radio" class="form-radio" {{ ($ApiKey->botstatus == "start")? "checked" : "" }} name="botstatus" value="start">
      <span class="ml-2">เริ่มทำงาน</span>
    </label>
    <label class="inline-flex items-center ml-6">
      <input type="radio" class="form-radio" {{ ($ApiKey->botstatus == "stop")? "checked" : "" }} name="botstatus" value="stop">
      <span class="ml-2">หยุดทำงาน</span>
    </label>
  </div>
    <div class="mt-4">


      <button class="px-4 py-1 font-light tracking-wider text-white bg-gray-900 rounded" type="submit">บันทึก</button>

    </div>
    <div class="mt-4">
        @if($ApiKey->botstatus == "start")
    <div class="px-5 py-3 mb-4 text-sm text-green-900 bg-green-100 border border-green-200 rounded-md" role="alert">
      <p class="font-medium text-gray-800">ตอนนี้บอทเริ่มทำงานอยู่</p>
    </div>
        @else
 <div class="px-5 py-3 mb-4 text-sm text-red-900 bg-red-100 border border-red-200 rounded-md" role="alert">
      <p class="font-medium text-gray-800">ตอนนี้บอทหยุดทำงานอยู่</p>
    </div>
        @endif
    </div>
</div>

  </form>
</div>
</div>

</x-app-layout>
