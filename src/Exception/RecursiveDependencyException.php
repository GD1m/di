<?php declare(strict_types=1);

namespace GDim\DI\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

final class RecursiveDependencyException extends RuntimeException implements ContainerExceptionInterface
{
    public function __construct(string $id, array $calls)
    {
        $callsString = implode(', ', $calls);

        parent::__construct("Recursive call of `{$id}`, other calls: {$callsString}");
    }
}
