<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use App\Models\User;
use RealRashid\SweetAlert\Facades\Alert;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $draft_invoices = Invoice::where('status', OrderStatusEnum::Draft->value)->count();
        $cancel_invoices = Invoice::where('status', OrderStatusEnum::Cancel->value)->count();
        $send_invoices = Invoice::where('status', OrderStatusEnum::Send->value)->count();


        return view('home', compact('draft_invoices', 'cancel_invoices', 'send_invoices'));
    }


    public  function change_lang($lang){
        if(in_array($lang , ["ar" , "en"])){
            User::where("id" ,  auth()->id())->update(["lang"=>$lang]);
            app()->setLocale($lang);
            Alert::toast(__("messages.done successfully") , "success");
            return back();
        }
    }
}
