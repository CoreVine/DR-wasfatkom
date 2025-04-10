<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Http\Controllers\Controller;
use App\Models\Language;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class LanguageController extends Controller
{

	public function __construct()
	{
		//$this->middleware('permission:setting_lang');
	}

	public function index()
	{
		$data = Language::orderDefaultActive();
		if (request('key_words') . '%') {
			$data =  $data->where('name', 'like', '%' . request('key_words') . '%')->orWhere('native_name', 'like', '%' . request('key_words') . '%');
		}

		$data =  $data->paginate(config('app.paginate_number'));
		$languages_active = Language::where('is_active', 1)->get();
		return view('admin.settings.languages', compact('data', 'languages_active'));
	}


	public function active_in_active($id)
	{
		$lang = Language::findOrFail($id);

		$count_active = Language::where('is_active', 1)->count();

		if ($lang->is_default && $lang->is_active == 1) {

			Alert::toast(__("messages.The default language cannot be deactivated"), "warning");
			return back();
		}


		if ($count_active == 1) {
			Alert::toast(__("messages.It is not possible to deactivate all languages"), "warning");
			return back();
		}


		$lang->is_active = !$lang->is_active;
		$lang->save();

		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}


	public function set_default($id)
	{
		$lang = Language::findOrFail($id);


		if (!$lang->is_active) {
			Alert::toast(__("messages.Activate the language first"), "warning");
			return back();
		}
		Language::where('id', '!=', $id)->update([
			'is_default' => 0
		]);


		$lang->is_default = 1;
		$lang->save();


		Alert::toast(__("messages.done successfully"), "success");
		return back();
	}
}
