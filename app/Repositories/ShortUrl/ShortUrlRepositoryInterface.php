<?php
namespace App\Repositories\ShortUrl;

use App\Repositories\RepositoryInterface;

interface ShortUrlRepositoryInterface extends RepositoryInterface
{
    public function createShortURL($url, $shortCode);
    public function findByShortCode($shortCode);
}
