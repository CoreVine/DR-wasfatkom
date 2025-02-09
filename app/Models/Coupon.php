<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function doctors(){
        return $this->hasMany(CouponDoctor::class);
    }
}
