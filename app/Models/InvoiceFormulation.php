<?php

namespace App\Models;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceFormulation extends Model
{
	use HasFactory;

	protected $guarded = ['id'];
	protected $table = 'invoice_formulations';

	public function invoice()
	{
		return $this->belongsTo(Invoice::class);
	}

	public function formulation()
	{
		return $this->belongsTo(Formulation::class);
	}

	public function getCreatedAtAttribute($value)
	{
		return date('d-m-Y h:i A', strtotime($value));
	}
}
