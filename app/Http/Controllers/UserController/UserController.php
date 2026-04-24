<?php

namespace App\Http\Controllers\UsersController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
class UserController extends Controller
{
   public function index(Request $request)
{
    $query = User::with('roles');

    if ($request->search) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%$search%")
              ->orWhere('email', 'like', "%$search%")
              ->orWhereHas('roles', function ($role) use ($search) {
                  $role->where('name', 'like', "%$search%");
              });
        });
    }

    $users = $query->get();

    if ($request->ajax()) {
        return view('Admin.Users.partials.user_table', compact('users'))->render();
    }

    return view('Admin.Users.index', compact('users'));
}

    public function create()
    {
        $roles = Role::all();
        return view('Admin.Users.create', compact('roles'));
    }

    public function loglogin(Request $request)
        {
          $query = ActivityLog::with('user')
                ->where('event', 'login')
                ->whereHas('user', function ($q) {
                    $q->whereHas('roles', function ($role) {
                        $role->where('name', '!=', 'user');
                    });
                });

            if ($request->search) {
                $search = $request->search;

                $query->where(function ($q) use ($search) {
                    $q->where('ip_address', 'like', "%$search%")
                    ->orWhere('device', 'like', "%$search%")
                    ->orWhere('browser', 'like', "%$search%")
                    ->orWhere('platform', 'like', "%$search%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%$search%")
                            ->orWhere('email', 'like', "%$search%");
                    });
                });
            }

            $logs = $query->latest()->get();

            if ($request->ajax()) {
                return view('Admin.Users.partials.log_table', compact('logs'))->render();
            }

            return view('Admin.Users.log_login', compact('logs'));
        }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($request->role);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('Admin.Users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        if ($request->password) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index');
    }

    public function destroy(User $user)
    {
        if ($user->hasRole('superadmin')) {
            return back();
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }
}