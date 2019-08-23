<?php declare(strict_types=1);

namespace GDim\DI;

use Closure;
use GDim\DI\Exception\NotFoundException;
use GDim\DI\Exception\RecursiveDependencyException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var ProviderInterface[]
     */
    private $providers;

    /**
     * @var array<string, mixed>
     */
    private $values = [];

    /**
     * @var string[]
     */
    private $providedKeys = [];

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
        if (array_key_exists($id, $this->values)) {
            return $this->values[$id];
        }

        $this->values[$id] = $this->getValue($id);

        return $this->get($id);
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
        $this->checkKeyNotProvidedYet($id);

        $result = $this
            ->getProvider($id)
            ->provide($id);

        $this->providedKeys[] = $id;

        return $this->transformResult($result);
    }

    private function checkKeyNotProvidedYet(string $id): void
    {
        if (in_array($id, $this->providedKeys, true)) {
            throw new RecursiveDependencyException($id, $this->providedKeys);
        }
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
