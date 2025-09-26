<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function admin(){
        return view('admin.dashboard');
    }

    public function tenant(){
        return view('tenant.dashboard');
    }

    public function landlord(){
        return view('landlord.dashboard');
    }
}
