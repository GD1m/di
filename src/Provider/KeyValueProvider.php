<?php declare(strict_types=1);

namespace GDim\DI\Provider;

use GDim\DI\Exception\NotFoundException;
use GDim\DI\Provider\Exception\KeyNotFoundException;
use GDim\DI\ProviderInterface;

final class KeyValueProvider implements ProviderInterface
{
    /**
     * @var array
     */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function provide(string $id)
    {
        if (!$this->canProvide($id)) {
            throw new KeyNotFoundException($id);
        }

        return $this->values[$id];
    }

    public function canProvide(string $id): bool
    {
        return array_key_exists($id, $this->values);
    }
}
