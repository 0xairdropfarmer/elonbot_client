<?php

namespace App\Http\Controllers;

use App\Models\OrderHistory;
use Illuminate\Http\Request;

class OrderHistoryController extends Controller
{
    public function index(){
        $orderHistory = OrderHistory::paginate(10);
        return view('orderhistory.index',compact('orderHistory'));
    }
}
