<?php declare(strict_types=1);

namespace GDim\DI\Loader;

use Closure;
use GDim\DI\LoaderInterface;
use Psr\Container\ContainerInterface;

final class Callback implements LoaderInterface
{
    /**
     * @var Closure
     */
    private $closure;

    public function __construct(Closure $closure)
    {
        $this->closure = $closure;
    }

    public function __invoke(ContainerInterface $container)
    {
        return ($this->closure)($container);
    }
}
