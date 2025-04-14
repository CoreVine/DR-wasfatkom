<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use Illuminate\Support\Facades\Log;

use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use App\Models\ProductTranslate;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;
use App\Models\Product;
use App\Models\Category;
use App\Models\Language;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Helpers\HelperTranslate;
use App\Http\Helpers\ResponseHelper;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\ProductRequest;
use App\Models\Favorite;
use App\Models\Supplier;
use Illuminate\Http\Response;

class ProductController extends Controller
{
  public function __construct()
  {
    $this->middleware('permission:show_product')->only(['index', 'export']);
    $this->middleware('permission:create_product')->only(['create', 'store']);
    $this->middleware('permission:edit_product')->only(['edit', 'update', 'active_inactive']);
    $this->middleware('permission:delete_product')->only('destroy');
  }

  public function index(Request $request)
  {
    $request_filter = "?";
    $index = 0;
    foreach ($request->query() as $key => $value) {
      if ($index == 0) {
        $request_filter .= $key . "=" . $value;
      } else {
        $request_filter .= "&" . $key . "=" . $value;
      }

      $index++;
    }
    $data = Product::with(['category', 'sub_category', 'supplier']);


    $data = $this->filter($data);


    $products = Product::select("id", "qty", "sale_qty", "remain_qty")->get();
    $categories = Category::select("id")->get();
    $suppliers = Supplier::select("id", 'name')->get();

    $sub_categories = [];
    if (request('category_id')) {
      $sub_categories = SubCategory::select("id")->where('category_id', request('category_id'))->get();
    }

    $supplier_name = Supplier::where('id', request('supplier_id'))->first()?->name;
    $product_name = Product::where('id', request('product_id'))->first()?->name;
    $category_name = Category::where('id', request('category_id'))->first()?->name;
    $sub_category_name = SubCategory::where('id', request('sub_category_id'))->first()?->name;

    $data = $data->paginate(config('app.paginate_number'));
    $favorites = Favorite::where('doctor_id', auth()->id())->with('product')->pluck("product_id")->toArray();

    return view('admin.products.index', compact('request_filter', 'data', 'favorites', 'products', 'categories', 'sub_categories', 'product_name', 'category_name', 'sub_category_name', 'suppliers', 'supplier_name'));
  }

  public function trashed(Request $request)
  {
    $request_filter = "?";
    $index = 0;

    foreach ($request->query() as $key => $value) {
      if ($index == 0) {
        $request_filter .= $key . "=" . $value;
      } else {
        $request_filter .= "&" . $key . "=" . $value;
      }

      $index++;
    }

    $data = Product::with(['category', 'sub_category', 'supplier'])->onlyTrashed();
    $data = $this->filter($data);

    $products = Product::select("id", "qty", "sale_qty", "remain_qty")->get();
    $categories = Category::select("id")->get();
    $suppliers = Supplier::select("id", 'name')->get();

    $sub_categories = [];

    if (request('category_id')) {
      $sub_categories = SubCategory::select("id")->where('category_id', request('category_id'))->get();
    }

    $supplier_name = Supplier::where('id', request('supplier_id'))->first()?->name;
    $product_name = Product::where('id', request('product_id'))->first()?->name;
    $category_name = Category::where('id', request('category_id'))->first()?->name;
    $sub_category_name = SubCategory::where('id', request('sub_category_id'))->first()?->name;

    $data = $data->paginate(config('app.paginate_number'));
    $favorites = Favorite::where('doctor_id', auth()->id())->with('product')->pluck("product_id")->toArray();

    return view('admin.products.trashed', compact('request_filter', 'data', 'favorites', 'products', 'categories', 'sub_categories', 'product_name', 'category_name', 'sub_category_name', 'suppliers', 'supplier_name'));
  }

  /* public function export()
  {
    $data = Product::orderBy('id', 'desc');
    $data = $this->filter($data);

    $data = $data->get();

    $export_data = [];

    foreach ($data as $item) {
      $export_data[] = [
        'id' => $item->id,
        __("messages_301.Product name") => $item->name,
        __("messages_301.Code") => $item->code,
        __("messages_303.barcode") => $item->barcode,
        __('messages_303.category name') =>  $item->category->name,
        __("messages_301.Sub category") =>  $item->sub_category?->name,
        __("messages_301.Supplier") =>  $item->supplier?->name,
        __("messages.Qty") => $item->qty,
        __("messages_301.Qty sale") => $item->sale_qty,
        __("messages_301.Qty remain") => $item->remain_qty,
        __("messages_301.Expire date") => $item->created_at_format,
        __("messages.Status") => $item->is_active,
        __("messages.Favorite") => $item->favorite ? 1 : 0,

        // Add other fields you want to include
      ];
    }
    return (new FastExcel(collect($export_data)))->download('Products.xlsx');
  } */

  public function export(Request $request)
  {
    return Excel::download(new ProductsExport($request), 'products.xlsx');
  }

  public function import(Request $request)
  {
    $request->validate([
      'file.*' => 'required|mimes:xlsx,xls,csv',
    ]);

    Excel::import(new ProductsImport, $request->file('file'));
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function create()
  {
    $languages = Language::active()->orderDefaultActive()->get();
    $categories = Category::active()->get();
    $suppliers = Supplier::select('id', 'name')->get();

    return view('admin.products.create', compact('languages', 'categories', 'suppliers'));
  }

  public function store(ProductRequest $request)
  {
    DB::beginTransaction(); // you are using commit, but missing this
    try {
      $data = $request->validated();
      Log::info('This is an info message.');

      // dd($data); // you can use this only to debug input

      if ($request->hasFile('image')) {
        $image = HelperFile::upload($request->file('image'), '/products/images');
        $data['image'] = $image['path'];
      }

      $data['remain_qty'] = $data['qty'];
      $tax = $data['tax'];

      $price_before_tax = $data['price'];
      $tax_value = ($tax / 100) * $price_before_tax;
      $price = $price_before_tax + $tax_value;

      $data['price'] = $price;
      $data['price_before_tax'] = $price_before_tax;

      if ($data['sub_category_id']) {
        $check = SubCategory::where('id', $data['sub_category_id'])
          ->where('category_id', $data['category_id'])
          ->first();

        if (!$check) {
          Alert::toast(__("messages_301.This sub category is not belongs to this category"), "error");
          return back()->withInput(); // preserve old input
        }
      }

      $product = Product::create($data);

      HelperFile::generate_barcode($product->id);

      ProductTranslate::create([
        'product_id' => $product->id,
        'lang' => 'ar',
        'name' => $request->name_ar,
        'description' => $request->description_ar ?? '',
      ]);

      ProductTranslate::create([
        'product_id' => $product->id,
        'lang' => 'en',
        'name' => $request->name_en,
        'description' => $request->description_en ?? '',
      ]);

      DB::commit();
      Alert::toast(__("messages.done successfully"), "success");

      return redirect()->route('admin.products.index');
    } catch (\Throwable $error) {
      DB::rollBack();
      Log::error("Error in ProductController@store: " . $error->getMessage());
      Alert::toast("Something went wrong!", "error");
      return back()->withInput(); // to keep the user input
    }
  }

  public function active_inactive($id)
  {
    $item = Product::findOrFail($id);
    $item->is_active = !$item->is_active;
    $item->save();

    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function restore_all()
  {
    Product::onlyTrashed()->restore();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function delete_all()
  {
    Product::query()->delete();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function show(string $id) {}

  public function edit(string $id)
  {
    $item = Product::findOrFail($id);
    $languages = Language::active()->orderDefaultActive()->get();
    $categories = Category::active()->get();
    $suppliers = Supplier::select('id', 'name')->get();
    $sub_categories = SubCategory::active()->get();

    $ar = ProductTranslate::where([
      'lang' => 'ar',
      'product_id' => $id
    ])->first();

    $en = ProductTranslate::where([
      'lang' => 'en',
      'product_id' => $id
    ])->first();

    $translations = ProductTranslate::where([
      'product_id' => $id
    ])->get();
    return view('admin.products.edit', compact('translations', 'en', 'ar', 'languages', 'categories', 'item', 'sub_categories', 'suppliers'));
  }

  public function update(ProductRequest $request, string $id)
  {
    try {
      $data = $request->validated();
      $item = Product::findOrFail($id);
      $old_image = $item->image;
      $old_barcode = $item->barcode;

      global $image_url;
      $image_url = $item->image;

      $tax = $data['tax'];

      $price_before_tax = $data['price_before_tax'];
      $tax_value = ($tax / 100) * $price_before_tax;
      $price = $price_before_tax + $tax_value;

      $data['price'] = $price;
      $data['price_before_tax'] = $price_before_tax;

      if ($request->hasFile('image')) {
        HelperFile::delete($old_image);
        $image = HelperFile::upload($request->file('image'), '/products/images');
        $data['image'] = $image['path'];
        $image_url = $data['image'];
      }

      if ($item->sale_qty != 0) {
        $old_qty = $item->qty;
        $new_qty = $data['qty'];
        $dif_qty = $new_qty - $old_qty;
        $data['remain_qty'] = $item->remain_qty + $dif_qty;
      } else {
        $data['remain_qty'] = $data['qty'];
      }

      $item->update([
        'category_id' => $data['category_id'],
        'supplier_id' => $data['supplier_id'] ?? null,
        'image' => $image_url,
        'barcode' => $data['barcode'],
        'qty' => $data['qty'],
        'code' => $data['code'],
        'remain_qty' => $data['remain_qty'],
        'price_before_tax' => $price_before_tax,
        'tax' => $tax,
        'price' => $price,
        'sub_category_id' => $request->input('sub_category_id') ?? null,
      ]);

      ProductTranslate::updateOrCreate([
        'product_id' => $item->id,
        'lang' => 'ar',
      ], [
        'name' => $request->name_ar,
        'product_id' => $item->id,
        'lang' => 'ar',
        'description' => $request->description_ar,
      ]);

      ProductTranslate::updateOrCreate([
        'product_id' => $item->id,
        'lang' => 'en',
      ], [
        'name' => $request->name_en,
        'product_id' => $item->id,
        'lang' => 'en',
        'description' => $request->description_en,
      ]);

      // HelperTranslate::set_translate($request, Product::class, $item->id);
      Alert::toast(__("messages.done successfully"), "success");
      return back();
    } catch (Throwable $error) {
      Log::error("Error in ProductController@update: " . $error->getMessage());
    }
  }

  public function destroy(string $id)
  {
    $products = Product::findOrFail($id);
    $products->delete();
    //HelperFile::delete($products->image);
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function force_delete(string $id)
  {
    $products = Product::findOrFail($id);
    $products->forceDelete();
    HelperFile::delete($products->image);
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function restore(string $id)
  {
    $products = Product::withTrashed()->findOrFail($id);
    $products->restore();
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }

  public function get_sub_category()
  {
    $category = Category::where('id', request('category_id'))->active()->first();
    $sub_categories = SubCategory::where('category_id', $category->id)->active()->get();

    return view('admin.products.inc.sub_categories', compact('sub_categories'));
  }

  public function filter($query)
  {
    $query = $query->when(request('product_id'), function ($q) {
      $q->where('id', request('product_id'));
    })->when(request('key_words'), function ($q) {
      $key_words = request('key_words');
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
      ->when(request('category_id'), function ($q) {
        $q->where('category_id', request('category_id'));
      })
      ->when(request('supplier_id'), function ($q) {
        $q->where('supplier_id', request('supplier_id'));
      })
      ->when(request('category_id') && request('sub_category_id'), function ($q) {
        $q->where('category_id', request('category_id'))->where('sub_category_id', request('sub_category_id'));
      })
      ->when(request('qty_low'), function ($q) {
        $q->where('qty', "<=", request('qty_low'));
      })
      ->when(request('qty_high'), function ($q) {
        $q->where('qty', ">=", request('qty_high'));
      })
      ->when(request('sale_qty_low'), function ($q) {
        $q->where('sale_qty', "<=", request('sale_qty_low'));
      })
      ->when(request('sale_qty_high'), function ($q) {
        $q->where('sale_qty', ">=", request('sale_qty_high'));
      })
      ->when(request('remain_qty_low'), function ($q) {
        $q->where('remain_qty', "<=", request('remain_qty_low'));
      })
      ->when(request('remain_qty_high'), function ($q) {
        $q->where('remain_qty', ">=", request('remain_qty_high'));
      })
      ->when(request('from_date'), function ($q) {
        $q->where('expire_date', ">=", request('from_date'));
      })
      ->when(request('to_date'), function ($q) {
        $q->where('expire_date', "<=", request('to_date'));
      });

    return $query;
  }

  public function ajax_add_or_remove_favorite()
  {
    $product = Product::findOrFail(request("product_id"));
    $is_favorite = Favorite::where("doctor_id", auth()->id())->where("product_id", request('product_id'))->first();
    $product_res = null;
    if (!$is_favorite) {
      $message = __("messages.The item has been successfully added to your favorites");

      $product_res = $product;
      Favorite::create([
        "product_id" => $product->id,
        "doctor_id" => auth()->id(),
      ]);
    } else {
      $message = __("messages.The item has been successfully removed from favorites");

      $is_favorite->delete();
    }

    return ResponseHelper::sendResponseSuccess(["product" => $product_res], Response::HTTP_OK, $message);
  }

  public function get_sub_category_filter()
  {
    $sub_categories = SubCategory::where('category_id', request('category_id'))->get();

    return view('admin.products.inc.sub_categories', compact('sub_categories'));
  }
}
