<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;
    protected $guarded=['id'];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function invoice(){
        return $this->belongsTo(Invoice::class);
    }
}
