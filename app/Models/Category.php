<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Models\Product;
use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, ModelTranslateTrait;
    protected $guarded = ['id'];

    protected $appends = ['name', 'description'];

    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

   

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }
}
