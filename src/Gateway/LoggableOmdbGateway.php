<?php
declare(strict_types=1);

namespace App\Gateway;

use App\Entity\Movie;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsDecorator(OmdbGateway::class, priority: 1)]
class LoggableOmdbGateway extends OmdbGateway
{
    public function __construct(
        private OmdbGateway $omdbGateway,
        private LoggerInterface $logger,
    )
    {
    }

    public function getPoster(Movie $movie): string
    {
        $this->logger->notice('OmdbGateway::getPoster was called.');
        return $this->omdbGateway->getPoster($movie);
    }

    public function getMovie(string $title): ?Movie
    {
        return $this->omdbGateway->getMovie($title);
    }
}