<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserListController extends Controller
{
    public function getListUser($perPage = 4)
    {
        $users = User::with('totalUrls', 'roles')
        ->select('id','name','email','is_verified','created_at')
        ->paginate($perPage);
        return response()->json(['users' => $users], 200);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $role = $request->input('role');
        $user->syncRoles([$role]);
        return response()->json(['message' => 'Vai trò của người dùng đã được cập nhật thành công'], 200);
    }
}
