<?php declare(strict_types=1);

namespace GDim\DI;

use GDim\DI\Exception\NotFoundException;
use Psr\Container\ContainerInterface;

class Container implements ContainerInterface
{
    /**
     * @var array
     */
    private $values;

    public function __construct(array $values)
    {
        $this->values = $values;
    }

    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException($id);
        }

        $result = $this->values[$id];

        if (is_callable($result)) {
            return $result($this);
        }

        return $result;
    }

    public function has($id): bool
    {
        return array_key_exists($id, $this->values);
    }
}
