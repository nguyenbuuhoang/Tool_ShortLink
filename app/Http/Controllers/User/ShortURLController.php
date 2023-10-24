<?php

namespace App\Http\Controllers\User;

use App\Models\ShortUrl;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Exports\ShortUrlsExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShortURL;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ShortUrlController extends Controller
{
    public function createShortURL(CreateShortURL $request)
    {
        $userId = Auth::check() ? Auth::user()->id : null;

        $url = $request->input('url');
        $shortCode = Str::random(4);
        $shortUrlLink = str_replace(['http://', 'https://'], '', url($shortCode));
        $qrCode = QrCode::size(200)->generate($shortUrlLink);
        // Kiểm tra nếu người dùng là null, thiết lập expired_at thành 30 phút
        $expiredAt = $userId ? now()->addDays(5) : now()->addMinutes(30);

        $shortUrl = ShortUrl::create([
            'url' => $url,
            'short_code' => $shortCode,
            'short_url_link' => $shortUrlLink,
            'clicks' => 0,
            'expired_at' => $expiredAt,
            'user_id' => $userId,
            'qrcode' => $qrCode,
        ]);

        return response()->json([
            'url' => $shortUrl->url,
            'short_url_link' => $shortUrl->short_url_link,
            'clicks' => $shortUrl->clicks,
            'status' => $shortUrl->status,
            'created_at' => $shortUrl->created_at,
            'expired_at' => $shortUrl->expired_at,
            'qrcode' => $qrCode,
        ]);
    }

    public function updateShortCode(Request $request, $id)
    {
        $shortUrl = ShortUrl::findOrFail($id);

        if (!Auth::check() || $shortUrl->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Không có quyền chỉnh sửa'], 403);
        }

        $shortCode = $request->input('short_code');

        $shortUrl->short_code = $shortCode;
        $shortUrl->short_url_link = str_replace(['http://', 'https://'], '', url($shortCode));

        $shortUrl->save();

        return response()->json(
            $shortUrl->only([
                'url',
                'short_url_link',
                'status',
            ])
        );
    }

    public function deleteShortURL($id)
    {
        $shortUrl = ShortUrl::find($id);
        if (!$shortUrl) {
            return response()->json(['error' => 'URL không tìm thấy'], 404);
        }
        if (!Auth::check() || $shortUrl->user_id !== Auth::user()->id) {
            return response()->json(['error' => 'Không có quyền chỉnh sửa'], 403);
        }
        $shortUrl->delete();
        return response()->json(['message' => 'URL đã được xóa thành công']);
    }
    public function redirectToURL($shortCode)
    {
        $shortUrl = ShortUrl::where('short_code', $shortCode)->first();

        if (!$shortUrl) {
            return response()->json(['error' => 'Short URL không tìm thấy'], 404);
        }
        if ($shortUrl->expired_at && now() > $shortUrl->expired_at) {
            return view('expired_code');
        }

        $shortUrl->increment('clicks');
        return redirect($shortUrl->url);
    }
    public function getShortURLsByUserId(Request $request, $userId, $perPage = 4)
    {
        $query = ShortUrl::where('user_id', $userId);

        $shortLink = $request->input('url');
        if (!empty($shortLink)) {
            $query->where('url', 'like', '%' . $shortLink . '%');
        }
        $sort_by = $request->input('sort_by', 'id');
        $sort_order = $request->input('sort_order', 'asc');
        $query->orderBy($sort_by, $sort_order);

        if ($request->has('export') && $request->input('export') === 'csv') {
            $shortUrls = $query->get();
            return Excel::download(new ShortUrlsExport($shortUrls), 'data_short_urls.csv');
        }

        $shortUrls = $query->paginate($perPage);
        return response()->json([
            'shortUrls' => $shortUrls,
        ], 200);
    }
    public function getTotalsByUserId($userId)
    {
        $totals = ShortUrl::where('user_id', $userId)
            ->selectRaw('COUNT(*) as totalShortLinks, SUM(clicks) as totalClicks')
            ->first();

        return response()->json([
            'totalShortLinks' => $totals->totalShortLinks,
            'totalClicks' => $totals->totalClicks,
        ], 200);
    }
}
