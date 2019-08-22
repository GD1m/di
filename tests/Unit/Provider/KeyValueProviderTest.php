<?php declare(strict_types=1);

namespace GDim\DI\Tests\Unit\Provider;

use GDim\DI\Provider\Exception\KeyNotFoundException;
use GDim\DI\Provider\KeyValueProvider;
use PHPUnit\Framework\TestCase;

class KeyValueProviderTest extends TestCase
{
    public function testCanProvide(): void
    {
        $provider = new KeyValueProvider([
            'id' => 'value',
        ]);

        $this->assertTrue($provider->canProvide('id'));
        $this->assertFalse($provider->canProvide('invalid id'));
    }

    public function testProvide(): void
    {
        $provider = new KeyValueProvider([
            'id' => 'value',
        ]);

        $this->assertSame('value', $provider->provide('id'));
    }

    public function testProvideNotExistsId(): void
    {
        $this->expectException(KeyNotFoundException::class);

        $provider = new KeyValueProvider([]);

        $provider->provide('invalid id');
    }
}
