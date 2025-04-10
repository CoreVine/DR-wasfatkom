<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductTranslate extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $fillable = ['name', 'description', 'lang', 'product_id'];
}
