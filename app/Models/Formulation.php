<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ModelTranslateTrait;
use App\Models\Category;
use Carbon\Carbon;


class Formulation extends Model
{
  use HasFactory, ModelTranslateTrait;
  protected $guarded = ['id'];
  protected $appends = ['name', 'description', 'created_at_format'];


  public function category()
  {
    return $this->belongsTo(Category::class);
  }

  public function sub_category()
  {
    return $this->belongsTo(SubCategory::class);
  }

  public function scopeActive($q)
  {
    return $q->where('is_active', 1);
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

  public function getImageAttribute($val)
  {
    if (!$val) {
      return "default-images/category.png";
    } else {
      return $val;
    }
  }
}
