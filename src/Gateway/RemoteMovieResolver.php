<?php
declare(strict_types=1);

namespace App\Gateway;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RemoteMovieResolver implements ValueResolverInterface
{
    public function __construct(
        private OmdbGateway $omdbGateway,
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
//        $title = $request->attributes->get('title');
//        $movie = $this->omdbGateway->getMovie($title);

        return [];
    }
}