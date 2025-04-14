<?php

namespace App\Enums;

enum PaymentTypeEnum: string
{
    case MyFatoora = 'My fatoora';
    case Tabby = 'Tabby';
    case UponReceipt = 'upon receipt';
    case Unpaid = 'Un paid';
    case Paid = 'Paid';

    public static function values()
    {
        $items = [];

        foreach (PaymentTypeEnum::cases() as $role) {
            $items[] = $role->value;
        }

        return  $items;
    }


    public static function key_values()
    {
        $items = [];
        foreach (PaymentTypeEnum::cases() as $role) {
            $items[$role->name] = $role->value;
        }

        return  $items;
    }
}
