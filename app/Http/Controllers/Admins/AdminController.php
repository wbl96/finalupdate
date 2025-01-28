<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Admins\GetAdminsDataRequest;
use App\Http\Requests\Admin\Admins\StoreAdminsRequest;
use App\Http\Requests\Admin\Admins\UpdateAdminPermissionsRequest;
use App\Http\Requests\Admin\Admins\UpdateAdminsRequest;
use App\Mail\PasswordResetMail;
use App\Models\Admin;
use App\Models\Department;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    public function index()
    {
        // get all admins
        $admins = Admin::all();
        // get all departments
        $departments = Department::all();
        // return view with all variables
        return view('admin.admins.index', compact('admins', 'departments'));
    }

    public function getAdmins(GetAdminsDataRequest $request)
    {
        // get admins
        $admins = Admin::all();
        // Retrieve requested columns
        $columns = request()->columns ? array_column(request()->columns, 'data') : [];
        // html columns
        $htmlColumns = empty($columns) ?  ['name', 'email', 'department', 'permissions', 'controls'] : $columns;
        // return data into datatables
        return DataTables::of($admins)
            ->addIndexColumn()
            ->addColumn('department', function ($admin) {
                // get admin department
                $dept = $admin->department ?? null;
                // check department
                if (!$dept)
                    return '<span class="text-danger">' . trans('global.unknown') . '</span>';
                return app()->getLocale() == 'ar' ? $dept->name_ar : $dept->name_en;
            })
            ->addColumn('permissions', function ($admin) {
                return '<button class="btn btn-sm btn-outline-dark m-1" onclick="showPermissions(\'' . $admin->id . '\', \'' . $admin->name . '\')"><i class="fa fa-eye"></i>&nbsp;' . trans('admins.show permissions') . '</button>';
            })
            ->addColumn('controls', function ($admin) {
                // button
                $btn = '<button class="btn btn-sm btn-info text-white m-1" onclick="showEdit(\'' . route('admin.admins.edit', [$admin]) . '\')"><i class="fa fa-eye"></i>&nbsp;' . trans('global.edit') . '</button>';
                $btn .= '<button class="btn btn-sm btn-danger text-white m-1" onclick="_delete(\'' . $admin->id . '\', \'' . $admin->name . '\')"><i class="fa fa-trash-alt"></i>&nbsp;' . trans('global.delete') . '</button>';
                $btn .= '<button class="btn btn-sm btn-warning text-white m-1" onclick="sendRestPassword(\'' . route('admin.admins.sendRestPassword', [$admin->email]) . '\')">' . trans('global.reset password') . '</button>';
                return $btn;
            })
            ->rawColumns($htmlColumns)
            ->make(true);
    }

    public function store(StoreAdminsRequest $request)
    {
        // start a DB transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // generate a temp password
            $data['password'] = Hash::make(now());
            // insert data into database
            $admin = Admin::create($data);
            // // send a reset password email
            // $this->sendResetPasswordEmail($data['email']);
            // commit transaction
            DB::commit();

            // success msg
            $msg = '<ul>';
            $msg .= '<li>' . trans('global.updated') . '</li>';
            $msg .= '<li>' . trans('admins.check reset password email') . '</li>';
            $msg .= '<li><a href="javascript:void(0);" style="color: #000" onclick="showPermissions(\'' . $admin->id . '\', \'' . $admin->name . '\')"><b>' . trans('global.click here') . '</b></a>&nbsp;' . trans('admins.to add permissions') . '</li>';
            $msg .= '</ul>';

            return response([
                'success' => $msg,
                'id' => $admin->id,
            ]);
        } catch (\Exception $ex) {
            // rollback BD transaction
            DB::rollBack();
            // return error message
            return response([
                'error' => ['msg' => [$ex->getMessage()]],
            ]);
        }
    }

    public function sendResetPasswordEmail($email)
    {
        // target route
        $route = 'admin.admins.sendRestPassword';
        $table = 'admin_password_reset_token';
        // send email
        return $this->resetPasswordService->sendResetPasswordEmail($email, $route, $table);
    }

    public function edit(string $id)
    {
        // get admin data
        $admin = Admin::where('id', $id)->first();
        // check admin data
        if (!$admin) {
            return response(['status' => 'error', 'msg' => trans('admins.not found')]);
        }
        // get all departments
        $departments = Department::all();
        // return view with data
        return view('admin.admins.edit', compact('admin', 'departments'));
    }

    public function update(UpdateAdminsRequest $request, string $id)
    {
        // get validated data
        $data = $request->validated();
        // check if password set
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        // get admin
        $admin = Admin::where('id', $id)->first();
        // check admin data
        if (!$admin) {
            return response(['status' => 'error', 'msg' => trans('admins.not found')]);
        }
        // update admin data
        $admin->update($data);
        // check auth id
        if (Auth::id() == $id) {
            // update data
            Auth::setUser($admin);
        }
        // return with success message
        return response([
            'success' => trans('global.updated'),
        ]);
    }

    public function destroy(string $id)
    {
        // get admin
        $admin = Admin::where('id', $id)->first();
        // check admin data
        if (!$admin) {
            return back()->withErrors(['status' => 'error', 'msg' => trans('admins.not found')])->throwResponse();
        }
        // delete admin
        $admin->delete();
        // return back with success message
        return redirect()->route('admin.admins.list')->with('success', trans('admins.deleted'));
    }

    public function showPermission($id)
    {
        // get admin
        $admin = Admin::where('id', $id)->first();
        // check admin data
        if (!$admin) {
            return response()->json(['status' => 'error', 'msg' => trans('global.not found')], 404);
        }
        // get admin permissions
        $permissions = $admin->getAllPermissions();
        // check if data type is set to return correct response
        if (request()->has('dataType')) {
            // if set then return view
            return view('admin.admins.permissions.permissions_modal', compact('admin', 'permissions'));
        }
        // if not set return json data
        return response()->json(['status' => 'success', 'msg' => $permissions]);
    }

    public function updatePermissions(UpdateAdminPermissionsRequest $request, string $id)
    {
        // get admin
        $admin = Admin::where('id', $id)->first();
        // check admin data
        if (!$admin) {
            return back()->withErrors(['status' => 'error', 'msg' => trans('admins.not found')])->throwResponse();
        }
        // assign all given permissions to given admin
        $admin->syncPermissions($request->input('permissions'));
        // return back with success message
        return back()->with('success', trans('global.updated'));
    }
}
