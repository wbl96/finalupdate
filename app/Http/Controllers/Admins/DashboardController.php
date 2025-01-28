<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Store;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // get statistics
        $providers = 0;
        $suppliers = User::all()->count();
        $stores = Store::all()->count();
        // return view
        return view('admin.dashboard.index', get_defined_vars());
    }

    public function updateFCM(){
        Admin::query()->where('id', auth()->id() ?? null)->update([
            'fcm_token' => request('fcm_token')
        ]);
    }
}
