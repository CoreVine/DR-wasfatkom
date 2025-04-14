<?php

namespace App\Services;

use Twilio\Rest\Client;

class NotificationService
{
  protected $twilio;

  public function __construct()
  {
    $this->twilio = new Client(
      env('TWILIO_SID'),
      env('TWILIO_AUTH_TOKEN')
    );
  }

  public function sendSMS($to, $message)
  {
    return $this->twilio->messages->create($to, [
      'from' => env('TWILIO_PHONE_NUMBER'),
      'body' => $message,
    ]);
  }

  public function sendWhatsApp($to, $message)
  {
    return $this->twilio->messages->create("whatsapp:" . $to, [
      'from' => "whatsapp:" . env('TWILIO_PHONE_NUMBER'),
      'body' => $message,
    ]);
  }
}
