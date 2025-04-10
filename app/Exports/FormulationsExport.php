<?php

namespace App\Exports;

use App\Models\Formulation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FormulationsExport implements FromCollection, WithHeadings
{

	public function collection()
	{
		return Formulation::with('all_translate') // Load translations
			->get()
			->map(function ($formulation) {
				return [
					'ID'           => $formulation->id,
					'Name'         => optional($formulation->all_translate->first())->name, // Get first translation
					'Description'  => optional($formulation->all_translate->first())->description,
					'Price'        => $formulation->price,
					'Barcode'      => $formulation->barcode,
					'SKU'          => $formulation->sku,
					'Code'         => $formulation->code,
					'Ingredients'  => $formulation->ingredients,
					'Is Active'    => $formulation->is_active,
					'Category ID'  => $formulation->category_id,
					'Sub Category' => $formulation->sub_category_id,
				];
			});
	}

	public function headings(): array
	{
		return [
			'ID',
			'Name',
			'Description',
			'Price',
			'Barcode',
			'SKU',
			'Code',
			'Ingredients',
			'Is Active',
			'Category ID',
			'Sub Category',
		];
	}
}
