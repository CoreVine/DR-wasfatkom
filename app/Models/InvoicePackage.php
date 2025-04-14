<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoicePackage extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function package(){
        return $this->belongsTo(Package::class);
    }
}
