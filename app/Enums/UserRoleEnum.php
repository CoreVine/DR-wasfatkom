<?php
namespace App\Enums;
enum UserRoleEnum:string {
    case Admin = 'admin';
    case Doctor = 'doctor';


    public static function values(){
        $items =[];

        foreach(UserRoleEnum::cases() as $role){
            $items[]=$role->value;
        }

        return  $items;

    }


    public static function key_values(){
        $items =[];
        foreach(UserRoleEnum::cases() as $role){
            $items[$role->name]=$role->value;

        }

        return  $items;

    }
}



