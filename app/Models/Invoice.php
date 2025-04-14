<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Enums\UserRoleEnum;
use App\Models\Scopes\InvoiceScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Invoice extends Model
{
  use HasFactory;

  protected $guarded = ['id'];
  protected $appends = ['created_at_format'];

  public function invoice_items()
  {
    return $this->hasMany(InvoiceItem::class);
  }

  public function invoice_packages()
  {
    return $this->hasMany(InvoicePackage::class);
  }

  public function invoice_formulations()
  {
    return $this->hasMany(InvoiceFormulation::class);
  }

  public function doctor()
  {

    return $this->belongsTo(User::class, 'doctor_id');
  }


  public function reviewer()
  {
    return $this->belongsTo(User::class, 'review_id');
  }

  public function getCreatedAtFormatAttribute()
  {
    return Carbon::parse($this->created_at)->format("Y-m-d h:i a");
  }


  protected static function booted(): void
  {
    static::addGlobalScope(new InvoiceScope);


    static::addGlobalScope('orderby', function (Builder $builder) {

      $builder->orderBy("id", "desc");
    });
  }


  public function coupon()
  {
    return $this->belongsTo(Coupon::class);
  }
}
