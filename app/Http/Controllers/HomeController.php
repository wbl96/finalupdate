<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // get class of auth user
        $class = get_class(Auth::user());
        // check guard check
        if ($class == 'App\Models\Admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($class == 'App\Models\User') {
            // check auth user type
            $type = Auth::user()->type;
            switch ($type) {
                case 'supplier':
                    return redirect()->route('supplier.dashboard');
                    break;
                case 'store':
                    return redirect()->route('store.dashboard');
                    break;
            }
        } else {
            dd();
        }
    }
}
