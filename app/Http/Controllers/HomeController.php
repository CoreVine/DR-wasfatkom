<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Enums\OrderStatusEnum;
use App\Models\User;
use Exception;
use RealRashid\SweetAlert\Facades\Alert;

use Twilio\Rest\Client;

class HomeController extends Controller
{

  public function send_whatsapp()
  {
    try {
      $phone = "+201554842200";
      $sid = "ACcf2a7665e1a2353add70f17c164f5fb8";
      $token = "90c8bded9fb0cd4180da6e9fcf9f3434";

      $client = new Client($sid, $token);
      $message = $client->messages
        ->create(
          "whatsapp:+201554842200",
          array(
            "from" => "whatsapp:+201123525123",
            "body" => "Your Message"
          )
        );

      dd("success");
    } catch (Exception $e) {
      dd($e->getMessage());
    }
  }

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('auth');
  }

  public function pending_approval()
  {
    return view('pending-approval');
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

    $user = User::find(2);
    $user->assignRole('admin');

    return view('home', compact('draft_invoices', 'cancel_invoices', 'send_invoices'));
  }

  public function change_lang($lang)
  {
    if (in_array($lang, ["ar", "en"])) {
      User::where("id", auth()->id())->update(["lang" => $lang]);
      app()->setLocale($lang);
      Alert::toast(__("messages.done successfully"), "success");
      return back();
    }
  }
}
