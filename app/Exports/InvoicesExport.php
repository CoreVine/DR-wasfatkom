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
    $query = Invoice::with(['invoice_items.product', 'doctor']);

    // Apply filters from request
    if ($this->request->client) {
      $query->where(function ($q) {
        $q->where("client_name", "like", "%" . $this->request->client . "%")
          ->orWhere("client_mobile", "like", "%" . $this->request->client . "%");
      });
    }

    foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {
      if ($this->request->$input) {
        $query->where($input, $this->request->$input);
      }
    }

    if ($this->request->from_date) {
      $query->whereDate("created_at", ">=", $this->request->from_date);
    }

    if ($this->request->to_date) {
      $query->whereDate("created_at", "<=", $this->request->to_date);
    }

    $invoices = $query->get();

    // Extract invoice_items into a flat structure
    $exportData = new Collection();

    foreach ($invoices as $invoice) {
      foreach ($invoice->invoice_items as $item) {
        $exportData->push([
          'Client' => $invoice->client_name,
          'Phone' => $invoice->client_mobile,
          'Doctor Name' => $invoice->doctor->name,
          'Invoice Number' => $invoice->invoice_num,
          'Status' => $invoice->status,
          'Cancel Reason' => $invoice->cancel_reason ?? 'Not-Available',
          'Payment Type' => $invoice->payment_type ?? '',
          'Code' => optional($item->product)->code ?? '',
          'Barcode' => optional($item->product)->barcode ?? '',
          'Name' => $item->product->name ?? '',
          'Price Before Tax' => $item->product->price_before_tax ?? '',
          'Discount' => $item->discount ?? '',
          'Total' => $item->total ?? '',
          'Brand' => optional($item->product->category)->name ?? '', // Assuming category relation exists
          'Category' => optional($item->product->sub_category)->name ?? '',
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
      'Payment Type',
      'Code',
      'Barcode',
      'Name',
      'Price Before Tax',
      'Discount',
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
      $row['Payment Type'],
      $row['Code'],
      $row['Barcode'],
      $row['Name'],
      $row['Price Before Tax'],
      $row['Discount'],
      $row['Brand'],
      $row['Category'],
    ];
  }
}
