<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Carbon\Carbon;
use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubCategory extends Model
{
    use HasFactory, ModelTranslateTrait;
    protected $guarded = ['id'];
    protected $appends = ['name', 'description', 'created_at_format'];

    public function scopeActive($q)
    {
        return $q->where('is_active', 1);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCreatedAtFormatAttribute($val)
    {

        if ($this->created_at) {

            return Carbon::parse($this->created_at)->format('d-m-Y h:i A');
        } else {
            return null;
        }
    }


    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }
    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y h:i A');
    }
}
