<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageTranslate extends Model
{
    use HasFactory;
    protected $fillable = [
        'lang',
        'name',
        'description',
        'package_id',
    ];
    protected $guarded = ['id'];
}
