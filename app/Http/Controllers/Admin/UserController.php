<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
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
}
