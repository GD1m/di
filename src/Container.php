<?php declare(strict_types=1);

namespace GDim\DI;

use Closure;
use GDim\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * @param ProviderInterface ...$providers
     */
    public function __construct(ProviderInterface ... $providers)
    {
        $this->providers = $providers;
    }

    /**
     * @param string $id
     * @return mixed
     */
    public function get($id)
    {
        return $this->getValue($id);
    }

    /**
     * @param string $id
     * @return bool
     */
    public function has($id): bool
    {
        return (bool)$this->findProvider($id);
    }

    private function getValue(string $id)
    {
        $provider = $this->getProvider($id);

        return $this->transformResult(
            $provider->provide($id)
        );
    }

    private function getProvider(string $id): ProviderInterface
    {
        $provider = $this->findProvider($id);

        if (!$provider) {
            throw new NotFoundException($id);
        }

        return $provider;
    }

    private function findProvider(string $id): ?ProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($provider->canProvide($id)) {
                return $provider;
            }
        }

        return null;
    }

    private function transformResult($result)
    {
        if ($result instanceof Closure) {
            return $result($this);
        }

        if ($result instanceof LoaderInterface) {
            return $result($this);
        }

        return $result;
    }
}
