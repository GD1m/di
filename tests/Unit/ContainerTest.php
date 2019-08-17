<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit;

use Closure;
use GDim\DI\Container;
use GDim\DI\Exception\NotFoundException;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerTest extends TestCase
{
    public function testGet(): void
    {
        $container = new Container([
            'a' => 'class a',
        ]);

        $this->assertEquals('class a', $container->get('a'));
    }

    public function testGetNotExistsId(): void
    {
        $this->expectException(NotFoundException::class);

        $container = new Container([]);

        $container->get('invalid');
    }

    public function testHas(): void
    {
        $container = new Container([
            'valid' => 'class a',
        ]);

        $this->assertTrue($container->has('valid'));
        $this->assertNotTrue($container->has('invalid'));
    }

    public function testClosureAsValue(): void
    {
        $container = new Container([
            'a' => static function (ContainerInterface $container) {
                return $container->get('dependency');
            },
            'dependency' => 'class a',
        ]);

        $this->assertEquals('class a', $container->get('a'));
    }

    public function testCallableValueNotInvokes(): void
    {
        $callable = 'get_class';

        $this->assertIsCallable($callable);

        $container = new Container([
            'a' => $callable,
        ]);

        $this->assertEquals($callable, $container->get('a'));
    }
}
