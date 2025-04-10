<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Carbon\Carbon;
use App\Models\Category;
use App\Traits\ModelTranslateTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
	use HasFactory, ModelTranslateTrait, SoftDeletes;
	protected $guarded = ['id'];

	protected $appends = ['name', 'description', 'created_at_format'];

	public function category()
	{
		return $this->belongsTo(Category::class);
	}

	public function commission_category()
	{
		return $this->belongsTo(Category::class)->where('is_commission', 1);
	}

	public function supplier()
	{
		return $this->belongsTo(Supplier::class);
	}

	public function sub_category()
	{
		return $this->belongsTo(SubCategory::class);
	}

	public function packages()
	{
		return $this->belongsToMany(Package::class, PackageProduct::class);
	}

	public function scopeActive($q)
	{
		return $q->where('is_active', 1);
	}

	public function getCreatedAtFormatAttribute($val)
	{

		if ($this->created_at) {

			return Carbon::parse($this->created_at)->format('Y-m-d');
		} else {
			return null;
		}
	}

	public function favorite()
	{
		return $this->hasOne(Favorite::class);
	}


	public function getImageAttribute($val)
	{
		if (!$val) {
			return "default-images/category.png";
		} else {
			return $val;
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
