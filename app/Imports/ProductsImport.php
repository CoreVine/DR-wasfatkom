<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\ProductTranslate;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProductsImport implements ToModel, WithHeadingRow
{
  public function model(array $row)
  {
    $p = Product::create([
      'price'            => $row['price'],
      'price_before_tax' => $row['price'],
      'barcode'          => $row['barcode'],
      'code'             => $row['code'],
      'qty'              => $row['qty'],
      'tax'              => $row['tax'],
      'remain_qty'       => $row['qty'],
      'sale_qty'       => 0,
      'category_id'      => $row['brand_id'],
      'sub_category_id'  => $row['category_id'],
      'supplier_id'      => $row['supplier_id'],
    ]);

    if ($p->id) {
      ProductTranslate::create([
        'lang'        => 'ar',
        'name'        => $row['ar_name'],
        'description' => $row['ar_description'],
        'product_id'  => (int)$p->id,
      ]);

      ProductTranslate::create([
        'lang'        => 'en',
        'name'        => $row['en_name'],
        'description' => $row['en_description'],
        'product_id'  => (int)$p->id,
      ]);
    }

    return $p;
  }
}
