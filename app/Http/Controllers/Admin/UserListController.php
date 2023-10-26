<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function getListUser()
    {
        $users = User::with('totalUrls')->get();

        $userList = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->getRoleNames(),
                'created_at' => $user->created_at,
                'is_verified' => $user->is_verified,
                'total_urls' => $user->totalUrls->first()?->total_url ?? 0,
            ];
        });

        return response()->json(['users' => $userList], 200);
    }
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = $request->input('role');
        $user->syncRoles([$role]);

        return response()->json(['message' => 'Vai trò của người dùng đã được cập nhật thành công'], 200);
    }
}
