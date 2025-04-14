<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function scopeActive($q){
        return $q->where('is_active' , 1);
    }


    public function scopeOrderDefaultActive($q){
        return $q->orderBy('is_default' , 'desc')->orderBy('is_active' , 'desc');
    }
}
