<?php

namespace App\Http\Controllers\UsersController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

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
        $hiddenRoles = [
            'admin' => ['superadmin'],
            'editor' => ['superadmin', 'admin'],
            'finance' => ['superadmin', 'admin', 'editor'],
            'staff' => ['superadmin', 'admin', 'editor', 'finance'],
            'user' => ['superadmin', 'admin', 'editor', 'finance'],
        ];

        $user = Auth::user();

        $excludeRoles = [];

        foreach ($hiddenRoles as $role => $rolesToHide) {
            if ($user->hasRole($role)) {
                $excludeRoles = $rolesToHide;
                break;
            }
        }

        $roles = Role::whereNotIn('name', $excludeRoles)->get();

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
        $allowedRoles = $this->allowedAssignableRoleNames();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'role' => ['required', Rule::in($allowedRoles)],
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
        $roles = Role::whereIn('name', $this->allowedAssignableRoleNames())->get();
        return view('Admin.Users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $allowedRoles = $this->allowedAssignableRoleNames();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => ['nullable', Password::min(8)->mixedCase()->numbers()],
            'role' => ['required', Rule::in($allowedRoles)],
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

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->is(Auth::user()) || $user->hasRole('superadmin')) {
            return back();
        }

        $user->delete();

        return redirect()->route('admin.users.index');
    }

    private function allowedAssignableRoleNames(): array
    {
        $actor = Auth::user();

        if ($actor->hasRole('superadmin')) {
            return Role::pluck('name')->all();
        }

        if ($actor->hasRole('admin')) {
            return Role::whereNotIn('name', ['superadmin'])->pluck('name')->all();
        }

        return Role::where('name', 'user')->pluck('name')->all();
    }
}
