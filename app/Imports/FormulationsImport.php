<?php

namespace App\Imports;

use App\Models\Formulation;
use App\Models\FormulationTranslate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FormulationsImport implements ToModel, WithHeadingRow
{
  public function model(array $row)
  {
    $p = Formulation::create([
      'price'          => $row['price'],
      'barcode'        => $row['barcode'],
      'code'           => $row['code'],
      'ingredients'           => $row['ingredients'],
      'category_id'   => $row['brand_id'],
      'sub_category_id' => $row['category_id'],
    ]);
    FormulationTranslate::create([
      'lang' => 'ar',
      'name' => $row['ar_name'],
      'description' => $row['ar_description'],
      'formulation_id' => $p->id
    ]);
    FormulationTranslate::create([
      'lang' => 'en',
      'name' => $row['en_name'],
      'description' => $row['en_description'],
      'formulation_id' => $p->id
    ]);
    return $p;
  }
}
