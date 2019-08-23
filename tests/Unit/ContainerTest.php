<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit;

use GDim\DI\Container;
use GDim\DI\Exception\NotFoundException;
use GDim\DI\Exception\RecursiveDependencyException;
use GDim\DI\Loader\Alias;
use GDim\DI\ProviderInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

class ContainerTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $provider;

    /**
     * @var Container
     */
    private $container;

    protected function setUp(): void
    {
        $this->provider = $this->createMock(ProviderInterface::class);

        assert($this->provider instanceof ProviderInterface);

        $this->container = new Container($this->provider);
    }

    public function testGetNotExistsId(): void
    {
        $this->expectException(NotFoundException::class);

        $this->container->get('invalid id');
    }

    public function testGet(): void
    {
        $this->provider
            ->expects($this->once())
            ->method('provide')
            ->with('id')
            ->willReturn('value');

        $this->provider
            ->expects($this->once())
            ->method('canProvide')
            ->with('id')
            ->willReturn(true);

        $this->assertSame('value', $this->container->get('id'));
    }

    public function testHas(): void
    {
        $this->provider
            ->expects($this->never())
            ->method('provide');

        $this->provider
            ->expects($this->exactly(2))
            ->method('canProvide')
            ->willReturnCallback(static function (string $id) {
                return $id === 'valid id';
            });

        $this->assertTrue($this->container->has('valid id'));
        $this->assertFalse($this->container->has('invalid id'));
    }

    public function testClosureAsValue(): void
    {
        $this->provider
            ->expects($this->exactly(2))
            ->method('canProvide')
            ->willReturn(true);

        $this->provider
            ->expects($this->exactly(2))
            ->method('provide')
            ->willReturnMap([
                [
                    'id',
                    static function (ContainerInterface $container) {
                        return $container->get('dependency');
                    }
                ],
                ['dependency', 'value'],
            ]);

        $this->assertSame('value', $this->container->get('id'));
    }

    public function testCallableValueNotInvokes(): void
    {
        $callable = 'get_class';

        $this->assertIsCallable($callable);

        $this->provider
            ->expects($this->once())
            ->method('canProvide')
            ->willReturn(true);

        $this->provider
            ->expects($this->once())
            ->method('provide')
            ->with('a')
            ->willReturn($callable);

        $this->assertSame($callable, $this->container->get('a'));
    }

    public function testAlias(): void
    {
        $this->provider
            ->expects($this->exactly(2))
            ->method('canProvide')
            ->willReturn(true);

        $this->provider
            ->expects($this->exactly(2))
            ->method('provide')
            ->willReturnMap([
                ['a', 'class a'],
                ['b', new Alias('a')],
            ]);

        $this->assertSame($this->container->get('a'), $this->container->get('b'));
    }

    public function testRecursiveDependency(): void
    {
        $this->expectException(RecursiveDependencyException::class);

        $this->provider
            ->expects($this->exactly(2))
            ->method('canProvide')
            ->willReturn(true);

        $this->provider
            ->expects($this->exactly(2))
            ->method('provide')
            ->willReturnMap([
                [
                    'a',
                    static function (ContainerInterface $container) {
                        return $container->get('b');
                    }
                ],
                [
                    'b',
                    static function (ContainerInterface $container) {
                        return $container->get('a');
                    }
                ],
            ]);

        $this->container->get('a');
    }

    public function testGetIdMultiply(): void
    {
        $this->provider
            ->expects($this->once())
            ->method('provide')
            ->with('id')
            ->willReturn('value');

        $this->provider
            ->expects($this->once())
            ->method('canProvide')
            ->with('id')
            ->willReturn(true);

        $this->container->get('id');
        $this->container->get('id');
    }
}
