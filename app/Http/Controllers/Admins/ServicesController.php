<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Services\GetServicesDataRequest;
use App\Http\Requests\Admin\Services\StoreServiceRequest;
use App\Http\Requests\Admin\Services\UpdateServiceRequest;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ServicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.services.index');
    }

    public function getServices(GetServicesDataRequest $request)
    {
        // get services
        $services = Service::all();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['name_ar', 'name_en', 'status', 'permissions', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($services)
            ->addIndexColumn()
            ->addColumn('status', function ($service) {
                if ($service->status == 'certified')
                    return '<span class="badge bg-success">' . trans('services.certified') . '</span>';
                return '<span class="badge bg-danger">' . trans('services.not certified') . '</span>';
            })
            ->addColumn('created_by', function ($service) {
                return $service->createdBy->name;
            })
            ->addColumn('updated_by', function ($service) {
                return $service->updatedBy->name;
            })
            ->addColumn('controls', function ($service) {
                // button
                $btn = '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'' . route('admin.services.edit', [$service]) . '\')"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $service->id . '\', \'' . $service->name_ar . '\')"><i class="fa fa-trash-alt"></i>&nbsp;' . trans('global.delete') . '</button>';
                return $btn;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceRequest $request)
    {
        // start DB transaction
        DB::beginTransaction();
        try {
            // create new service
            $service = Service::create($request->validated());
            // commit DB transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('services.inserted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // get service
        $service = Service::findOrFail($id);
        if (request()->json()) {
            return view('admin.services.edit_modal', compact('service'));
        }
        // return view with service data
        return response()->json([
            'success' => false
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServiceRequest $request, String $id)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // update service
            $service = Service::findOrFail($id)->update($data);
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.updated'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // delete service
            Service::findOrFail($id)->delete();
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('global.deleted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }
}
