<?php

namespace App\Models\Scopes;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Models\User;
use App\Enums\UserRoleEnum;
use App\Models\AdminDoctor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class InvoiceScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        //
        if (request()->is('admin*') || request()->is('home')) {

            $user_id = auth()->id();
            $user = User::where('id', $user_id)->first();
//            if ($user->type->value == UserRoleEnum::Admin->value && !$user->hasPermissionTo('admins')) {
//                $doctors_id = $user->is_all_doctor == 1 ? User::where('type', UserRoleEnum::Doctor->value)->pluck('id')->toArray() : AdminDoctor::where('admin_id', $user->id)->pluck('user_id')->toArray();
//                $builder->whereIn('doctor_id', $doctors_id);
//            } elseif ($user->type->value == UserRoleEnum::Doctor->value) {
//                $builder->where('doctor_id', $user->id);
//            }
        }
    }
}
