<?php

namespace App\Exports;

use App\Models\Invoice;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class InvoicesExport implements FromCollection, WithHeadings, WithMapping
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function collection()
  {
    $query = Invoice::with([
      'invoice_items' => fn($q) => $q->with([
        'product' => fn($q) => $q->with('category')
      ]),
      'doctor',
      'reviewer'
    ])->withCount('invoice_items');

    // Apply filters from request
    if (request('client')) {
      $query->where(function ($q) {
        $q->where("client_name", "like", "%" . request('client') . "%")
          ->orWhere("client_mobile", "like", "%" . request('client') . "%");
      });
    }

    foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {
      if (request($input)) {
        $query->where($input, request($input));
      }
    }

    if (request('from_date')) {
      $query->whereDate("created_at", ">=", request('from_date'));
    }

    if (request('to_date')) {
      $query->whereDate("created_at", "<=", request('to_date'));
    }

    // Paginate the results to match the index function
    $invoices = $query->get();

    // Extract invoice_items into a flat structure
    $exportData = new Collection();

    foreach ($invoices as $invoice) {
      foreach ($invoice->invoice_items as $item) {
        $exportData->push([
          'Client' => $invoice->client_name,
          'Phone' => $invoice->client_mobile,
          'Doctor Name' => $invoice->doctor->name ?? 'N/A',
          'Invoice Number' => $invoice->invoice_num,
          'Status' => $invoice->status,
          'Cancel Reason' => $invoice->cancel_reason ?? 'N/A',
          'The use' => $item->the_use ?? 'N/A',
          'Payment Type' => $invoice->payment_type ?? '',
          'Code' => optional($item->product)->code ?? 'N/A',
          'Barcode' => optional($item->product)->barcode ?? 'N/A',
          'Name' => $item->product->name ?? '',
          'Price Before Tax' => $item->product->price_before_tax ?? '',
          'Tax' => $item->product->tax . '%' ?? '0',
          'Item Price' => $item->price ?? '0',
          'Quantity' => $item->qty ?? '0',
          'Discount' => $item->discount ?? '0',
          'Sub Total' => $item->price ?? '0',
          'Total' => $item->total ?? '0',
          'Brand' => optional($item->product->category)->name ?? 'N/A',
          'Category' => optional($item->product->sub_category)->name ?? 'N/A',
        ]);
      }
    }

    return $exportData;
  }

  public function headings(): array
  {
    return [
      'Client',
      'Phone',
      'Doctor Name',
      'Invoice Number',
      'Status',
      'Cancel Reason',
      'The use',
      'Payment Type',
      'Code',
      'Barcode',
      'Name',
      'Price Before Tax',
      'Tax',
      'Item Price',
      'Quantity',
      'Discount',
      'Sub Total',
      'Total',
      'Brand',
      'Category',
    ];
  }

  public function map($row): array
  {
    return [
      $row['Client'],
      $row['Phone'],
      $row['Doctor Name'],
      $row['Invoice Number'],
      $row['Status'],
      $row['Cancel Reason'],
      $row['The use'],
      $row['Payment Type'],
      $row['Code'],
      $row['Barcode'],
      $row['Name'],
      $row['Price Before Tax'],
      $row['Tax'],
      $row['Item Price'],
      $row['Quantity'],
      $row['Discount'],
      $row['Sub Total'],
      $row['Total'],
      $row['Brand'],
      $row['Category'],
    ];
  }
}
