<?php

namespace App\Exports;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function collection()
  {
    // Apply the same filters as index()
    $query = Product::with(['category', 'sub_category', 'supplier']);
    $query = $this->filter($query);

    $products = $query->get();

    // Format Data
    $exportData = new Collection();
    foreach ($products as $product) {
      $exportData->push([
        'ID' => $product->id,
        'Name' => $product->name,
        'Category' => optional($product->category)->name ?? 'N/A',
        'Sub Category' => optional($product->sub_category)->name ?? 'N/A',
        'Supplier' => optional($product->supplier)->name ?? 'N/A',
        'Stock Qty' => $product->qty,
        'Sold Qty' => $product->sale_qty,
        'Remaining Qty' => $product->remain_qty,
        'Expire Date' => $product->expire_date ?? 'N/A',
      ]);
    }

    return $exportData;
  }

  public function headings(): array
  {
    return [
      'ID', 'Name', 'Category', 'Sub Category', 'Supplier', 'Stock Qty', 'Sold Qty', 'Remaining Qty', 'Expire Date'
    ];
  }

  public function map($row): array
  {
    return [
      $row['ID'],
      $row['Name'],
      $row['Category'],
      $row['Sub Category'],
      $row['Supplier'],
      $row['Stock Qty'],
      $row['Sold Qty'],
      $row['Remaining Qty'],
      $row['Expire Date'],
    ];
  }

  // Use the same filter logic as in the controller
  public function filter($query)
  {
    return $query->when($this->request->product_id, function ($q) {
      $q->where('id', $this->request->product_id);
    })->when($this->request->key_words, function ($q) {
      $key_words = $this->request->key_words;
      $q->whereHas('all_translate', function ($subQuery) use ($key_words) {
        $subQuery->where('name', 'like', "%" . $key_words . "%");
      })->orWhereHas("category", function ($q) use ($key_words) {
        $q->whereHas('all_translate', function ($subQuery) use ($key_words) {
          $subQuery->where('name', 'like', "%" . $key_words . "%");
        });
      })->orWhereHas("supplier", function ($subQuery) use ($key_words) {
        $subQuery->where('name', 'like', "%" . $key_words . "%");
      });
    })
      ->when($this->request->category_id, function ($q) {
        $q->where('category_id', $this->request->category_id);
      })
      ->when($this->request->supplier_id, function ($q) {
        $q->where('supplier_id', $this->request->supplier_id);
      })
      ->when($this->request->category_id && $this->request->sub_category_id, function ($q) {
        $q->where('category_id', $this->request->category_id)->where('sub_category_id', $this->request->sub_category_id);
      })
      ->when($this->request->qty_low, function ($q) {
        $q->where('qty', "<=", $this->request->qty_low);
      })
      ->when($this->request->qty_high, function ($q) {
        $q->where('qty', ">=", $this->request->qty_high);
      })
      ->when($this->request->sale_qty_low, function ($q) {
        $q->where('sale_qty', "<=", $this->request->sale_qty_low);
      })
      ->when($this->request->sale_qty_high, function ($q) {
        $q->where('sale_qty', ">=", $this->request->sale_qty_high);
      })
      ->when($this->request->remain_qty_low, function ($q) {
        $q->where('remain_qty', "<=", $this->request->remain_qty_low);
      })
      ->when($this->request->remain_qty_high, function ($q) {
        $q->where('remain_qty', ">=", $this->request->remain_qty_high);
      })
      ->when($this->request->from_date, function ($q) {
        $q->where('expire_date', ">=", $this->request->from_date);
      })
      ->when($this->request->to_date, function ($q) {
        $q->where('expire_date', "<=", $this->request->to_date);
      });
  }
}
