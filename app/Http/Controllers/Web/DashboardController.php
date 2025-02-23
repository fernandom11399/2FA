<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     *  @return \Illuminate\View\View
     */
    public function index()
    {
        return view('dashboard');
    }
}
