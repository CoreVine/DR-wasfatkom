<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\OrderStatusEnum;
use App\Enums\UserRoleEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    protected $appends = ['is_block_text', 'avatar'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'type' => UserRoleEnum::class
    ];

    public function doctors()
    {
        return $this->hasMany(AdminDoctor::class);
    }


    public function getIsBlockTextAttribute()
    {
        return $this->is_block ? __('messages.Blocked') : __('messages.Active');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'doctor_id');
    }

    public function paid_invoice()
    {
        return $this->belongsTo(Invoice::class, 'doctor_id')->where('status', OrderStatusEnum::Paid->value);
    }


    public static function scopeOnlyDoctor($q){
        if(request()->is('admin/doctors') ){

            if(!auth()->user()->hasPermissionTo('admins') && !auth()->user()->is_all_doctor){

                $ids_doctors = AdminDoctor::where('admin_id' , auth()->id())->pluck('user_id')->toArray();

                return  $q->whereIn("id" , $ids_doctors);

            }
        }

        return  $q;

    }
    public function getAvatarAttribute()
    {
        if ($this->iamge) {
            return asset($this->image);
        }

        return asset('default-images/user-image.png');
    }
}
