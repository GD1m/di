<?php declare(strict_types=1);

namespace GDim\DI;

use Psr\Container\ContainerInterface;

interface LoaderInterface
{
    public function __invoke(ContainerInterface $container);
}
