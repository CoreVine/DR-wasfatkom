<?php

namespace App\Models;

use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Carbon\Carbon;

class Package extends Model
{
  use HasFactory, ModelTranslateTrait;

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

  public function getCreatedAtAttribute($value)
  {
    return Carbon::parse($value)->format('d-m-Y h:i A');
  }

  public function getUpdatedAtAttribute($value)
  {
    return Carbon::parse($value)->format('d-m-Y h:i A');
  }
}
