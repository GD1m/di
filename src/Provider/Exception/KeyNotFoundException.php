<?php declare(strict_types=1);

namespace GDim\DI\Provider\Exception;

use GDim\DI\ProviderExceptionInterface;
use LogicException;

final class KeyNotFoundException extends LogicException implements ProviderExceptionInterface
{
    public function __construct(string $id)
    {
        parent::__construct("Invalid key `{$id}`");
    }
}
