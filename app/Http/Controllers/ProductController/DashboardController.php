<?php

namespace App\Http\Controllers\ProductController;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $role = $user->getRoleNames()->first();
        $allUser = User::with('roles')->get();
        $totalUsers = User::count();
        return view('SuperAdmin.Dashboard.index', compact('user', 'role', 'allUser', 'totalUsers'));
    }
}