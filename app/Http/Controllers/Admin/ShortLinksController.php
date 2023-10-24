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

    public function getShortURL()
    {
        $shortUrls = ShortUrl::with('user')->get();

        $shortUrlData = [];

        foreach ($shortUrls as $shortUrl) {

            $userName = $shortUrl->user ? $shortUrl->user->name : 'Khách N/A';
            $shortUrlData[] = [
                'user_id' => $shortUrl->user_id,
                'user_name' => $userName,
                'url' => $shortUrl->url,
                'short_url_link' => $shortUrl->short_url_link,
                'total_clicks' => $shortUrl->clicks,
                'expired_at' => $shortUrl->expired_at,
                'status' => $shortUrl->status,
            ];
        }

        return response()->json($shortUrlData);
    }
}
