<?php
declare(strict_types=1);

namespace App\Gateway;

use Symfony\Contracts\Cache\CacheInterface;

class CacheableOmdbGateway extends OmdbGateway
{
    private $actualGateway;
    private $cache;

    public function __construct(
        OmdbGateway $actualGateway,
        CacheInterface $cache
    )
    {
        $this->actualGateway = $actualGateway;
        $this->cache = $cache;
    }

    public function getPosterByMovieTitle(string $title): string
    {
        $posterKey = sprintf('poster_%s', md5($title));
        return $this->cache->get($posterKey, function() use($title) {
            return $this->actualGateway->getPosterByMovieTitle($title);
        });
    }
}