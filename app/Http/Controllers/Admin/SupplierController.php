<?php

namespace App\Http\Controllers\Admin;

/*

Done by Nofalseo Software Services
nofalseo.com \ info@nofalseo.com

*/


use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Requests\Admin\SupplierRequest;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->middleware('permission:show_supplier')->only(['index', 'export']);
        $this->middleware('permission:create_supplier')->only(['create', 'store']);
        $this->middleware('permission:edit_supplier')->only(['edit', 'update']);
        // $this->middleware('permission:delete_supplier')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Supplier::orderBy('id', 'desc');

        if (request('key_words')) {
            $key_words = '%' . request('key_words') . '%';
            $data->where(function ($query) use ($key_words) {
                $query->where('name', 'like', $key_words);
            });
        }
        $data = $data->paginate(config('app.paginate_number'));
        return view('admin.suppliers.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {


        $data = $request->validated();


        Supplier::create($data);

        Alert::toast(__("messages.done successfully"), "success");
        return back();
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
        $item = Supplier::findOrFail($id);
        return view('admin.suppliers.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SupplierRequest $request, string $id)
    {


        $item = Supplier::findOrFail($id);
        $data = $request->validated();

        $item->update($data);
        Alert::toast(__("messages.done successfully"), "success");
        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
