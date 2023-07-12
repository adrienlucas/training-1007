<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\Cache\CacheInterface;

#[AsDecorator(OmdbGateway::class)]
class CacheableOmdbGateway extends OmdbGateway
{
    public function __construct(
        private OmdbGateway $omdbGateway,
        private CacheInterface $cache,
    ) {
    }

    public function getPoster(Movie $movie): string
    {
        $cacheKey = 'movie_poster_'.$movie->getId();

        return $this->cache->get(
            $cacheKey,
            fn() => $this->omdbGateway->getPoster($movie)
        );
    }
}