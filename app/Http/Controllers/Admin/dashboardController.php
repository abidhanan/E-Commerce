<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        // $user = Auth::user();
        // $role = $user->getRoleNames()->first();
        // $allUser = User::with('roles')->get();
        // $totalUsers = User::count();
        return view('Admin.Dashboard.index');
    }
}