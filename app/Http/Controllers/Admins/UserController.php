<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\StoreUserRequest;
use App\Http\Requests\Admin\Users\UpdateUserRequest;
use App\Models\Store;
use App\Models\User;
use App\Services\ResetPasswordService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Http\Request;
use Exception;

class UserController extends Controller
{
    public $resetPasswordService;

    public function __construct(ResetPasswordService $resetPasswordService)
    {
        $this->resetPasswordService = $resetPasswordService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // target title && title
        $targetType = request()->type;
        $title = trans('users.the users') . ' - ' . $targetType;
        return view('admin.users.list', compact('title', 'targetType'));
    }

    public function showTypes(Request $request)
    {
        try {
            if ($request->ajax()) {
                // تسجيل للتحقق
                \Log::info('Request Type:', ['type' => $request->type]);

                // الاستعلام الأساسي
                $stores = Store::query();

                // تسجيل عدد المتاجر
                \Log::info('Total Stores:', ['count' => $stores->count()]);

                // إضافة التحديدات الأساسية
                $stores = $stores->select([
                    'stores.id',
                    'stores.name',
                    'stores.email',
                    'stores.mobile'
                ]);

                // تحميل العلاقات
                $stores = $stores->with([
                    'wallet',
                    'wallet.transactions' => function($query) {
                        $query->where('is_paid', false)
                              ->where('type', 'credit_used');
                    }
                ]);

                // تسجيل الاستعلام النهائي
                \Log::info('Final Query:', [
                    'sql' => $stores->toSql(),
                    'bindings' => $stores->getBindings()
                ]);

                return DataTables::of($stores)
                    ->addIndexColumn()
                    ->addColumn('type', function ($store) {
                        return 'متجر';
                    })
                    ->addColumn('wallet_balance', function ($store) {
                        return $store->wallet ? number_format($store->wallet->balance ?? 0, 2) : '0.00';
                    })
                    ->addColumn('unpaid_amount', function ($store) {
        //                if (!$store->wallet) return '0.00';
                        
                        $unpaidAmount = $store->wallet->transactions
                            ->sum('amount');
                        
                        return number_format($unpaidAmount, 2);
                    })
                    ->addColumn('action', function ($store) {
                        $buttons = '<div class="btn-group">';
                        
                        // زر التعديل
                        $buttons .= '<button class="btn btn-sm btn-import text-white m-1" onclick="showEdit(\'/admins/admin/users/stores/' . $store->id . '/edit\')">
                            <i class="bi bi-eye"></i>&nbsp;تعديل
                        </button>';
                        
                        // زر تسديد المبالغ
                        if ($store->wallet && $store->wallet->transactions->count() > 0) {
                            $buttons .= '<button class="btn btn-success btn-sm m-1" onclick="updateUnpaidAmount(' . $store->id . ')">
                                <i class="bi bi-check-circle"></i>&nbsp;تسديد المبالغ
                            </button>';
                        }
                        
                        // زر الحذف
                        $buttons .= '<button class="btn btn-danger btn-sm m-1" onclick="_delete(\'' . $store->id . '\', \'' . $store->name . '\')">
                            <i class="bi bi-trash"></i>&nbsp;حذف
                        </button>';
                        
                        $buttons .= '</div>';
                        
                        return $buttons;
                    })
                    ->rawColumns(['action'])
                    ->toJson();
            }

            return view('admin.users.list', [
                'title' => 'المتاجر',
                'targetType' => 'store'
            ]);

        } catch (Exception $e) {
            \Log::error('ShowTypes Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'error' => 'حدث خطأ في النظام: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'حدث خطأ في النظام');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // page title
        $title = trans('users.the users') . ' - ' . trans('users.add new');
        // return view
        return view('admin.users.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // generate a temp password
            $data['password'] = Hash::make('123456789');
            // target type
            $type = $request->validated('type');
            // check type
            if ($type == 'store') {
                Store::create($data);
            } elseif ($type == 'supplier') {
                User::create($data);
            }
            // // send a reset password email
            $this->sendResetPasswordEmail($data['email']);
            // commit transaction
            DB::commit();
            // return back with success message
            return back()->with('success', trans('users.inserted'));
        } catch (\Exception $e) {
            // rollback Db transaction
            DB::rollBack();
            return back()->withErrors(['error' => $e->getMessage()])->withInput()->throwResponse();
        }
    }

    public function sendResetPasswordEmail($email)
    {
        // target route
        $route = 'admin.admins.sendRestPassword';
        $table = 'password_reset_tokens';
        // send email
        return $this->resetPasswordService->sendResetPasswordEmail($email, $route, $table);
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // page title
        $title = trans('users.the users') . ' - ' . trans('users.edit');
        // get user
        $user = User::findOrFail($id);
        $user->type = 'supplier';
        if (request()->json()) {
            return view('admin.users.edit_modal', compact('user'));
        }
        // return view with user data
        return view('admin.users.edit', compact('user', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editStore(Store $user, $id)
    {
        // page title
        $title = trans('users.the stores') . ' - ' . trans('users.edit');
        // get user
        $user = Store::findOrFail($id);
        $user->type = 'store';
        if (request()->json()) {
            return view('admin.users.edit_modal', compact('user'));
        }
        // return view with user data
        return view('admin.users.edit', compact('user', 'title'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get validated data
            $data = $request->validated();
            // update user
            $user->update($data);
            // check if email was changed
            if ($user->wasChanged('email')) {
                // update user password
                $user->password = Hash::make('123456789');
                $user->save();
                // // send a reset password email
                // $this->sendResetPasswordEmail($user->email);
            }
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
     * Update the specified resource in storage.
     */
    public function updateStore(UpdateUserRequest $request, string $id)
    {
        // start Db transaction
        DB::beginTransaction();
        try {
            // get store
            $store = Store::findOrFail($id);
            // get validated data
            $data = $request->validated();
            // update user
            $store->update($data);
            // check if email was changed
            if ($store->wasChanged('email')) {
                // update store password
                $store->password = Hash::make('123456789');
                $store->save();
                // // send a reset password email
                // $this->sendResetPasswordEmail($store->email);
            }
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
    public function destroy(User $user)
    {
        //
    }

    public function markTransactionsAsPaid(Store $store)
    {
        try {
            \DB::beginTransaction();

            // تسجيل للتحقق
            \Log::info('Marking transactions as paid for store:', [
                'store_id' => $store->id,
                'store_name' => $store->name
            ]);

            if (!$store->wallet) {
                throw new \Exception('لا يوجد محفظة لهذا المتجر');
            }

            // الحصول على المعاملات غير المسددة
            $unpaidTransactions = $store->wallet->transactions()
                ->where('is_paid', false)
                ->where('type', 'credit_used')
                ->get();

            \Log::info('Unpaid transactions found:', [
                'count' => $unpaidTransactions->count(),
                'transactions' => $unpaidTransactions->toArray()
            ]);

            if ($unpaidTransactions->isEmpty()) {
                throw new \Exception('لا توجد معاملات غير مسددة');
            }

            // تحديث حالة المعاملات
            foreach ($unpaidTransactions as $transaction) {
                $transaction->update([
                    'is_paid' => true,
                    'paid_at' => now()
                ]);
            }

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تسديد المبالغ بنجاح'
            ]);

        } catch (\Exception $e) {
            \DB::rollBack();
            
            \Log::error('Error marking transactions as paid:', [
                'store_id' => $store->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
