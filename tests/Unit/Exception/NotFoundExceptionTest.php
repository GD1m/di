<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit\Exception;

use GDim\DI\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundExceptionTest extends TestCase
{
    public function testImplementsPsrInterface(): void
    {
        $this->assertInstanceOf(NotFoundExceptionInterface::class, new NotFoundException('id'));
    }

    public function testMessage(): void
    {
        $this->assertSame('Container key `id` not found', (new NotFoundException('id'))->getMessage());
    }
}
