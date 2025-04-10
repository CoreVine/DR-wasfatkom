<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use RealRashid\SweetAlert\Facades\Alert;
use App\Models\Invoice;
use App\Models\User;
use App\Enums\UserRoleEnum;

class SettingController extends Controller
{
	public function index($page)
	{
		$settings = Setting::where('page', $page)->get()->groupBy(function ($item) {
			return $item->group;
		});
		return view("admin.settings.page", compact("settings", "page"));
	}


	public function update(Request $request, $group)
	{

		$groups_pages = Setting::pluck("group")->toArray();
		if (!in_array($group, $groups_pages)) {
			abort(404);
		}

		$validation = Setting::where('group', $group)->pluck("validation", "key")->toArray();
		$data = $request->validate($validation);
		foreach ($data  as $key => $val) {
			Setting::where("key", $key)->update(["value" => $val]);
		}


		Cache::forget("app_settings");
		Cache::forget("app_settings_config");


		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}

	/**
	 * Display a listing of the resource.
	 */
	public function invoices()
	{
		$id = auth()->user()->id;
		$data = Invoice::where('doctor_id', $id)->with(['doctor', 'reviewer'])->withCount('invoice_items');
		$reviewers = User::where("type", UserRoleEnum::Admin->value)->select("id", "name", "email")->get();

		if (request('client')) {
			$data = $data->where(function ($q) {
				$q->where("client_name", "like", "%" . request('client') . "%")->orWhere("client_mobile", "like", "%" . request('client') . "%");
			});
		}

		foreach (['doctor_id', 'review_id', 'status', 'invoice_num'] as $input) {

			if (request($input)) {
				$data = $data->where($input, request($input));
			}
		}


		if (request('from_date')) {
			$data = $data->whereDate("created_at", ">=", request('from_date'));
		}


		if (request('to_date')) {
			$data = $data->whereDate("created_at", "<=", request('to_date'));
		}


		$data = $data->paginate(config('app.paginate_number'));


		$review_sel = User::where("type", UserRoleEnum::Admin->value)->where("id", request('doctor_id'))->first()?->name;


		return view("admin.settings.invoices", compact('data', 'reviewers', 'review_sel'));
	}
}
