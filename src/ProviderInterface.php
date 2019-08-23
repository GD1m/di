<?php declare(strict_types=1);

namespace GDim\DI;

interface ProviderInterface
{
    public function provide(string $id);

    public function canProvide(string $id): bool;
}
