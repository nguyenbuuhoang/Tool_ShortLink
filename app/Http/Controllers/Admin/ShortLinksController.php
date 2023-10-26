<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ShortUrl;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShortLinksController extends Controller
{
    public function getTotal()
    {
        $totals = [
            'total_users' => User::count(),
            'total_short_url' => ShortUrl::count(),
            'total_clicks' => ShortUrl::sum('clicks'),
        ];

        return response()->json($totals);
    }

    public function getShortURL(Request $request)
    {
        $perPage = $request->input('per_page', 4);
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'asc');
        $name = $request->input('name');

        $query = ShortUrl::with('user:id,name')
            ->select('id', 'url', 'short_url_link', 'short_code', 'clicks', 'status', 'expired_at', 'created_at', 'user_id');

        if ($name) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }

        $query->orderBy($sort_by, $sort_order);

        $shortUrls = $query->paginate($perPage);

        return response()->json($shortUrls);
    }
    public function updateShortURL(Request $request, $id)
    {
        $shortUrl = ShortUrl::findOrFail($id);

        $shortUrl->update([
            'short_code' => $request->input('short_code'),
            'short_url_link' => str_replace(['http://', 'https://'], '', url($request->input('short_code'))),
            'status' => $request->input('status'),
            'expired_at' => $request->input('expired_at'),
        ]);

        return response()->json(['message' => 'Short URL đã được cập nhật thành công.']);
    }


    public function deleteShortURL($id)
    {
        $shortUrl = User::findOrFail($id);
        $shortUrl->delete();
        return response()->json(['message' => 'Đã xóa short Url thành công'], 200);
    }
}
