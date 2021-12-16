<?php

namespace Francerz\OAuth2\Tests;

use Francerz\OAuth2\ScopeHelper;
use PHPUnit\Framework\TestCase;

class ScopeHelperTest extends TestCase
{
    public function testToArray()
    {
        $this->assertEquals([], ScopeHelper::toArray([]));
        $this->assertEquals([], ScopeHelper::toArray(''));
        $this->assertEquals([], ScopeHelper::toArray(null));
        $this->assertEquals([], ScopeHelper::toArray(true));
        $this->assertEquals([], ScopeHelper::toArray(false));
        $this->assertEquals([], ScopeHelper::toArray(10));
        $this->assertEquals(['scp1'], ScopeHelper::toArray('scp1'));
        $this->assertEquals(['scp1', 'scp2'], ScopeHelper::toArray('scp1 scp2'));
        $this->assertEquals(['scp1', 'scp2', 'scp3'], ScopeHelper::toArray('scp1 scp2 scp3'));
    }

    public function testToString()
    {
        $this->assertEquals('', ScopeHelper::toString([]));
        $this->assertEquals('', ScopeHelper::toString(''));
        $this->assertEquals('', ScopeHelper::toString(null));
        $this->assertEquals('', ScopeHelper::toString(true));
        $this->assertEquals('', ScopeHelper::toString(false));
        $this->assertEquals('', ScopeHelper::toString(10));
        $this->assertEquals('scp1', ScopeHelper::toString(['scp1']));
        $this->assertEquals('scp1 scp2', ScopeHelper::toString(['scp1', 'scp2']));
        $this->assertEquals('scp1 scp2 scp3', ScopeHelper::toString(['scp1', 'scp2', 'scp3']));
    }

    public function testMatchAny()
    {
        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp1'));
        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp2'));
        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp3'));
        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp4'));

        $this->assertFalse(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp0'));
        $this->assertFalse(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp5'));
        $this->assertFalse(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp0 scp5'));

        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp0 scp1'));
        $this->assertTrue(ScopeHelper::matchAny('scp1 scp2 scp3 scp4', 'scp1 scp5'));
    }

    public function testMatchAll()
    {
        $this->assertTrue(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp1'));
        $this->assertTrue(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp2'));
        $this->assertTrue(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp3'));
        $this->assertTrue(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp4'));

        $this->assertFalse(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp0'));
        $this->assertFalse(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp5'));
        $this->assertFalse(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp0 scp5'));

        $this->assertFalse(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp0 scp1'));
        $this->assertFalse(ScopeHelper::matchAll('scp1 scp2 scp3 scp4', 'scp1 scp5'));
    }

    public function testMerge()
    {
        $this->assertEquals(['scp1'], ScopeHelper::merge([], 'scp1'));
        $this->assertEquals(['scp1', 'scp2'], ScopeHelper::merge([], 'scp1 scp2'));
        $this->assertEquals(['scp1', 'scp2'], ScopeHelper::merge('scp1', 'scp2'));
        $this->assertEquals(['scp1', 'scp2', 'scp3'], ScopeHelper::merge('scp1', 'scp2 scp3'));
        $this->assertEquals(['scp1', 'scp2', 'scp3'], ScopeHelper::merge('scp1 scp2', 'scp3'));
        $this->assertEquals(['scp2', 'scp1', 'scp3'], array_values(ScopeHelper::merge('scp2', 'scp1 scp2 scp3')));
    }

    public function testMergeString()
    {
        $this->assertEquals('scp1', ScopeHelper::mergeString('', 'scp1'));
        $this->assertEquals('scp1 scp2', ScopeHelper::mergeString('', 'scp1 scp2'));
        $this->assertEquals('scp1 scp2', ScopeHelper::mergeString('scp1', 'scp2'));
        $this->assertEquals('scp1 scp2 scp3', ScopeHelper::mergeString('scp1', 'scp2 scp3'));
        $this->assertEquals('scp1 scp2 scp3', ScopeHelper::mergeString('scp1 scp2', 'scp3'));
        $this->assertEquals('scp1 scp2 scp3', ScopeHelper::mergeString('scp1 scp2', 'scp2 scp3'));
    }
}
