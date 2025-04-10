<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use Throwable;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\SubCategory;
use App\Models\Favorite;
use App\Models\Product;
use App\Models\Language;
use Illuminate\Http\Request;
use App\Http\Helpers\HelperApp;
use App\Http\Helpers\HelperFile;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Rap2hpoutre\FastExcel\FastExcel;
use App\Http\Helpers\HelperTranslate;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\CategoryRequest;

class CategoryController extends Controller
{
	public function __construct()
	{
		$this->middleware('permission:show_category')->only(['index', 'export']);
		$this->middleware('permission:create_category')->only(['create', 'store']);
		$this->middleware('permission:edit_category')->only(['edit', 'update']);
		$this->middleware('permission:delete_category')->only('destroy');
	}
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
		$data = Category::orderBy('id', 'desc');

		if (request('key_words')) {
			$key_words = '%' . request('key_words') . '%';
			$data->where(function ($query) use ($key_words) {
				$query->whereHas('all_translate', function ($subQuery) use ($key_words) {
					$subQuery->where('name', 'like', $key_words);
				});
			});
		}
		$data = $data->paginate(config('app.paginate_number'));
		return view('admin.categories.index', compact('data'));
	}

	// public function export()
	// {
	//     $data = Category::orderBy('id', 'desc');
	//     if (request('key_words')) {
	//         $key_words = '%' . request('key_words') . '%';
	//         $data->where(function ($query) use ($key_words) {
	//             $query->whereHas('all_translate', function ($subQuery) use ($key_words) {
	//                 $subQuery->where('name', 'like', $key_words)
	//                     ->orWhere('description', 'like', $key_words);
	//             });
	//         });
	//     }

	//     $data = $data->get();
	//     return (new FastExcel($data))->download('Example.xlsx');
	// }

	public function active_inactive($id)
	{
		$item = Category::findOrFail($id);
		$item->is_active = !$item->is_active;
		$item->save();

		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}

	/**
	 * Show the form for creating a new resource.
	 */
	public function create()
	{
		$languages = Language::active()->orderDefaultActive()->get();
		return view('admin.categories.create', compact('languages'));
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(CategoryRequest $request)
	{
		DB::beginTransaction();
		try {

			$data = $request->validated();
			if ($request->hasFile('image')) {
				$file = HelperFile::upload($request->file('image'), 'categories');
				$data['image'] = $file['path'];
			}

			$data['is_commission'] = $request->is_commission ? true : false;


			$categories = Category::create($data);
			HelperTranslate::set_translate($request, Category::class, $categories->id);
			DB::commit();

			Alert::toast(__("messages.done successfully"), "success");
			return back();
		} catch (Throwable $e) {

			DB::rollBack();

			HelperApp::set_log_catch("Store categories", $e->getMessage());

			Alert::toast(__("messages.An error occurred in data entry"), "error");
			return back();
		}
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 */
	public function edit(string $id)
	{
		$item = Category::findOrFail($id);
		$languages = Language::active()->orderDefaultActive()->get();
		return view('admin.categories.edit', compact('languages', 'item'));
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(CategoryRequest $request, string $id)
	{
		DB::beginTransaction();

		try {

			$item = Category::findOrFail($id);
			$data = $request->validated();
			$old_image = $item->image;
			if ($request->hasFile('image')) {

				HelperFile::delete($old_image);
				$file = HelperFile::upload($request->file('image'), 'categories');
				$data['image'] = $file['path'];
			}
			$data['is_commission'] = $request->is_commission ? true : false;

			$item->update($data);


			HelperTranslate::set_translate($request, Category::class, $item->id);
			DB::commit();
			Alert::toast(__("messages.done successfully"), "success");
			return back();
		} catch (Throwable $e) {

			DB::rollBack();

			HelperApp::set_log_catch("update Category", $e->getMessage());

			Alert::toast(__("messages.An error occurred in data entry"), "error");
			return back();
		}
	}

	public function filter($query)
	{
		$query = $query->when(request('product_id'), function ($q) {
			$q->where('id', request('product_id'));
		})->when(request('key_words'), function ($q) {
			$key_words = request('key_words');
			$q->whereHas('all_translate', function ($subQuery) use ($key_words) {
				$subQuery->where('name', 'like', "%" . $key_words . "%");
			})->orWhereHas("category",  function ($q) use ($key_words) {
				$q->whereHas('all_translate', function ($subQuery) use ($key_words) {
					$subQuery->where('name', 'like', "%" . $key_words . "%");
				});
			})->orWhereHas("supplier",  function ($subQuery) use ($key_words) {

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

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		$categories = Category::findOrfail($id);
		$categories->delete();
		HelperFile::delete($categories->image);

		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}

	public function show_category_products(Request $request, string $id)
	{
		$category = Category::with('all_translate')->find($id);
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
		$data = Product::with(['category', 'sub_category', 'supplier'])->where('category_id', $id);

		$data = $this->filter($data);

		$products = Product::select("id", "qty", "sale_qty", "remain_qty")->get();
		$categories = Category::select("id")->get();
		$suppliers = Supplier::select("id", 'name')->get();

		$sub_categories = [];
		if (request('category_id')) {
			$sub_categories = SubCategory::select("id")->where('category_id',  request('category_id'))->get();
		}

		$supplier_name = Supplier::where('id', request('supplier_id'))->first()?->name;
		$product_name = Product::where('id', request('product_id'))->first()?->name;
		$category_name = Category::where('id', request('category_id'))->first()?->name;
		$sub_category_name = SubCategory::where('id', request('sub_category_id'))->first()?->name;

		$data = $data->paginate(config('app.paginate_number'));
		$favorites = Favorite::where('doctor_id', auth()->id())->with('product')->pluck("product_id")->toArray();

		return view('admin.categories.products', compact('request_filter', 'data', 'favorites', 'products', 'categories', 'sub_categories', 'product_name', 'category_name', 'sub_category_name', 'suppliers', 'supplier_name', 'category'));
	}
}
