<?php declare(strict_types=1);

namespace GDim\DI\Loader;

use GDim\DI\LoaderInterface;
use Psr\Container\ContainerInterface;

final class Alias implements LoaderInterface
{
    /**
     * @var string
     */
    private $id;

    public function __construct(string $id)
    {
        $this->id = $id;
    }

    public function __invoke(ContainerInterface $container)
    {
        return $container->get($this->id);
    }
}
