<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory,  ModelTranslateTrait;
    protected $guarded = ['id'];

    protected $appends = ['name', 'description'];

    public function products()
    {
        return $this->belongsToMany(Product::class, PackageProduct::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
