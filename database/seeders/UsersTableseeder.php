<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableseeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {



        $admin = User::create([
            "name" => "Wasftkom",
            "email" => fake()->email(),
            "password" => bcrypt(123456789),
            "type" => "admin"
        ]);













        $permissions = array_merge($this->admin_permissions());


        $permission_db = [];

        foreach ($permissions as $permission) {
            $permission_db[] = [
                "name" => $permission,
                "guard_name" => "web"
            ];
        }
        DB::table('permissions')->insert($permission_db);

        $admin->givePermissionTo($this->admin_permissions());
    }


    private function admin_permissions()
    {
        return  [
            "admins",

            "create_doctor",
            "show_doctor",
            "edit_doctor",
            "block_doctor",
            "delete_doctor",


            "create_product",
            "show_product",
            "edit_product",
            "delete_product",

            "create_supplier",
            "show_supplier",
            "edit_supplier",
            "delete_supplier",


            "create_category",
            "show_category",
            "edit_category",
            "delete_category",
            "active_category",

            "create_sub_category",
            "show_sub_category",
            "edit_sub_category",
            "delete_sub_category",
            "active_sub_category",


            "create_package",
            "show_package",
            "edit_package",
            "delete_package",


            "create_invoice",
            "show_invoice",
            "edit_invoice",
            "delete_invoice",
            "cancel_invoice",
            "send_invoice",
            "review_invoice",

            "create_coupon",
            "show_coupon",
            "edit_coupon",
            "active_coupon",


        ];
    }
}
