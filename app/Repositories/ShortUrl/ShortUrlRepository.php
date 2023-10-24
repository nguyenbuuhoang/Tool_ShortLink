<?php
namespace App\Repositories\ShortUrl;

use App\Models\ShortUrl;
use App\Repositories\BaseRepository;
use App\Repositories\ShortUrl\ShortUrlRepositoryInterface;

class ShortUrlRepository extends BaseRepository implements ShortUrlRepositoryInterface
{
    //lấy model tương ứng
    public function getModel()
    {
        return ShortUrl::class;
    }
    public function createShortURL($url, $shortCode)
    {
        return ShortUrl::create([
            'url' => $url,
            'short_code' => $shortCode
        ]);
    }

    public function findByShortCode($shortCode)
    {
        return ShortUrl::where('short_code', $shortCode)->first();
    }
}
