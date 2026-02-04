<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // middleware applied via routes; remove to avoid constructor errors
    }

    public function index(Request $request)
    {
        $totalUsers = User::count();
        return view('admin.dashboard', compact('totalUsers'));
    }
}
