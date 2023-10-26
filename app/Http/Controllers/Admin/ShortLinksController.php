<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\ShortUrl;
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

    public function getShortURL($perPage = 4)
    {
        $shortUrls = ShortUrl::with('user:id,name')
            ->select('id', 'url', 'short_url_link', 'short_code', 'clicks', 'status', 'expired_at', 'created_at', 'user_id')
            ->paginate($perPage);

        return response()->json($shortUrls);
    }
}
