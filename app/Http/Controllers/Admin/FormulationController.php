<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/

use App\Imports\FormulationsImport;
use App\Imports\ProductsImport;
use Throwable;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\FormulationsExport;
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
use App\Http\Requests\Admin\FormulationRequest;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Favorite;
use Illuminate\Http\Response;
use App\Models\Formulation;
use App\Models\FormulationTranslation;

class FormulationController extends Controller
{
	public function __construct() {}

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
		$data = Formulation::with(['category', 'sub_category']);
		$data = $this->filter($data);

		$formulations = Formulation::select("id")->get();
		$categories = Category::select("id")->get();
		$sub_categories = [];

		if (request('category_id')) {
			$sub_categories = SubCategory::select("id")->where('category_id',  request('category_id'))->get();
		}

		$formulation_name = Formulation::where('id', request('formulation_id'))->first()?->name;
		$category_name = Category::where('id', request('category_id'))->first()?->name;
		$sub_category_name = SubCategory::where('id', request('sub_category_id'))->first()?->name;

		$data = $data->paginate(config('app.paginate_number'));

		return view('admin.formulations.index', compact('request_filter', 'data', 'formulations', 'categories', 'sub_categories', 'formulation_name', 'category_name', 'sub_category_name'));
	}

	/*
	public function export()
	{
		$data = Formulation::orderBy('id', 'desc');
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
				__("messages_301.Expire date") => $item->created_at_format,
				__("messages.Status") => $item->is_active,

				// Add other fields you want to include
			];
		}
		return (new FastExcel(collect($export_data)))->download('Formulations.xlsx');
	}
 */

	public function export()
	{
		return Excel::download(new FormulationsExport, 'formulations.xlsx');
	}

	public function create()
	{
		$languages = Language::active()->orderDefaultActive()->get();
		$categories = Category::active()->get();

		return view('admin.formulations.create', compact('languages', 'categories'));
	}

	public function store(FormulationRequest $request)
	{
		DB::beginTransaction();

		try {
			$data = $request->validated();
      global $image_url;
			if ($request->hasFile('image')) {
				$image = HelperFile::upload($request->file('image'), '/products/images');
				$data['image'] = $image['path'];
			}

			if ($data['sub_category_id']) {
				$check = SubCategory::where('id', $data['sub_category_id'])->where('category_id', $data['category_id'])->first();

				if (!$check) {
					Alert::toast(__("messages_301.This sub category is not belongs to this category"), "error");
					return back();
				}
			}
			$formulations = Formulation::create($data);
			// HelperFile::generate_barcode($products->id);

			HelperTranslate::set_translate($request, Formulation::class, $formulations->id);
			DB::commit();

			Alert::toast(__("messages.done successfully"), "success");

			return back();
		} catch (Throwable $e) {

			DB::rollBack();

			HelperApp::set_log_catch("Store Formulation", $e->getMessage());

			Alert::toast(__("messages.An error occurred in data entry"), "error");
			return back();
		}
	}

	public function active_inactive($id)
	{
		$item = Formulation::findOrFail($id);
		$item->is_active = !$item->is_active;
		$item->save();

		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}

	public function show(string $id) {}

	public function edit(string $id)
	{
		$item = Formulation::findOrFail($id);
		$languages = Language::active()->orderDefaultActive()->get();
		$categories = Category::active()->get();
		$sub_categories = SubCategory::active()->get();

		return view('admin.formulations.edit', compact('languages', 'categories', 'item', 'sub_categories'));
	}

	public function update(FormulationRequest $request, string $id)
	{
		DB::beginTransaction();

		try {
			$data = $request->validated();
			$item = Formulation::findOrFail($id);
			$old_image = $item->image;
			$old_barcode = $item->barcode;
			if ($request->hasFile('image')) {
				HelperFile::delete($old_image);
				$image = HelperFile::upload($request->file('image'), '/formulations/images');
				$data['image'] = $image['path'];
			}

			$item->update([
				...$data,
				'sub_category_id' => $request->input('sub_category_id') ?? null,
			]);
			HelperTranslate::set_translate($request, Formulation::class, $item->id);
			DB::commit();
			Alert::toast(__("messages.done successfully"), "success");
			return back();
		} catch (Throwable $e) {
			dd($e);
			DB::rollBack();
			HelperApp::set_log_catch("update formulations", $e->getMessage());
			Alert::toast(__("messages.An error occurred in data entry"), "error");
			return back();
		}
	}

	public function destroy(string $id)
	{
		$products = Formulation::findOrFail($id);
		$products->delete();
		HelperFile::delete($products->image);
		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}

	public function get_sub_category()
	{
		$category = Category::where('id', request('category_id'))->active()->first();
		$sub_categories = SubCategory::where('category_id', $category->id)->active()->get();

		return view('admin.formulations.inc.sub_categories', compact('sub_categories'));
	}

	public function filter($query)
	{
		$query = $query->when(request('formulation_id'), function ($q) {
			$q->where('id', request('formulation_id'));
		})->when(request('key_words'), function ($q) {
			$key_words = request('key_words');
			$q->whereHas('all_translate', function ($subQuery) use ($key_words) {
				$subQuery->where('name', 'like', "%" . $key_words . "%");
			})->orWhereHas("category",  function ($q) use ($key_words) {
				$q->whereHas('all_translate', function ($subQuery) use ($key_words) {
					$subQuery->where('name', 'like', "%" . $key_words . "%");
				});
			});
		})
			->when(request('category_id'), function ($q) {
				$q->where('category_id', request('category_id'));
			})

			->when(request('category_id') && request('sub_category_id'), function ($q) {
				$q->where('category_id', request('category_id'))->where('sub_category_id', request('sub_category_id'));
			});

		return $query;
	}

	public function get_sub_category_filter()
	{
		$sub_categories = SubCategory::where('category_id', request('category_id'))->get();
		return view('admin.formulations.inc.sub_categories', compact('sub_categories'));
	}

  public function import(Request $request)
  {
    $request->validate([
      'file.*' => 'required|mimes:xlsx,xls,csv',
    ]);

    Excel::import(new FormulationsImport, $request->file('file'));
    Alert::toast(__("messages.done successfully"), "success");
    return back();
  }
}
