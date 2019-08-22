<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit\Provider\Exception;

use GDim\DI\Provider\Exception\KeyNotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

class KeyNotFoundExceptionTest extends TestCase
{
    public function testImplementsPsrInterface(): void
    {
        $this->assertInstanceOf(ContainerExceptionInterface::class, new KeyNotFoundException('id'));
    }

    public function testMessage(): void
    {
        $this->assertSame('Invalid key `id`', (new KeyNotFoundException('id'))->getMessage());
    }
}
