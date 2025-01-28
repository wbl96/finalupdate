<?php

namespace App\Http\Controllers\Suppliers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('supplier.dashboard.index');
    }

    public function updateFCM(){
        User::query()->where('id', auth()->id() ?? null)->update([
            'fcm_token' => request('fcm_token')
        ]);
    }
}
