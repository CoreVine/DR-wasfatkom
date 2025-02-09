<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Traits\ModelTranslateTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class Post extends Model
{
    use HasFactory ,  ModelTranslateTrait ,   SoftDeletes;
    protected $guarded=['id'];
    protected $appends=["title" , "content" , "slug" , "meta_description" , "created_at_format" , "deleted_at_format"];


    public function getCreatedAtFormatAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->created_at)->format('Y-m-d');
    }

    public function getDeletedAtFormatAttribute()
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $this->deleted_at)->format('Y-m-d');
    }


}
