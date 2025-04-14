<?php

namespace App\Enums;

enum OrderStatusEnum: string
{
  case Draft = 'draft';
  case Review = 'reviewed';
  case Done = 'done';
  case UnderDelivery = 'under_delivery';
  case Paid = 'paid';
  case Cancel = 'cancel';
  case Send = 'send';

  public static function values()
  {
    $items = [];

    foreach (OrderStatusEnum::cases() as $role) {
      $items[] = $role->value;
    }

    return $items;
  }


  public static function key_values()
  {
    $items = [];
    foreach (OrderStatusEnum::cases() as $role) {
      $items[$role->name] = $role->value;
    }

    return $items;
  }
}
