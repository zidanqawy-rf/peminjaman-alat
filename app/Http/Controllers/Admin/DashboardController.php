<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
        // middleware applied via routes; remove to avoid constructor errors
    }

    public function index(Request $request)
    {
        return view('admin.dashboard');
    }
}
