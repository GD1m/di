<?php declare(strict_types=1);

namespace GDim\DI\Exception;

use Psr\Container\NotFoundExceptionInterface;
use RuntimeException;

final class NotFoundException extends RuntimeException implements NotFoundExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct("Container key `{$id}` not found");
    }
}
