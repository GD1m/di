<?php declare(strict_types=1);

namespace GDim\DI;

use Psr\Container\ContainerInterface;

interface ProviderInterface
{
    public function provide(string $id);

    public function canProvide(string $id): bool;
}
