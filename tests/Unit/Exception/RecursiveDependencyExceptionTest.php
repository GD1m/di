<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit\Exception;

use GDim\DI\Exception\RecursiveDependencyException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerExceptionInterface;

class RecursiveDependencyExceptionTest extends TestCase
{
    public function testImplementsPsrInterface(): void
    {
        $this->assertInstanceOf(
            ContainerExceptionInterface::class,
            new RecursiveDependencyException('id', ['id'])
        );
    }

    public function testMessage(): void
    {
        $this->assertSame(
            'Recursive call of `id`, other calls: id',
            (new RecursiveDependencyException('id', ['id']))->getMessage()
        );
    }
}
