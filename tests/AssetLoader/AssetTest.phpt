<?php

namespace HeavenProject\Tests\AssetLoader;

use Tester;
use Tester\Assert;
use HeavenProject\AssetLoader\Asset;

require __DIR__ . '/../bootstrap.php';

class AssetTest extends Tester\TestCase
{
    public function testSetTimestamp()
    {
        $asset = new Asset(1, 1);
        $asset->setTimestamp(10);
        Assert::equal(10, $asset->getTimestamp());
    }

    public function testGetTimestamp()
    {
        $asset = new Asset(1, 1);
        Assert::equal(1, $asset->getTimestamp());
    }

    public function testSetVersion()
    {
        $asset = new Asset(1, 1);
        $asset->setVersion(10);
        Assert::equal(10, $asset->getVersion());
    }

    public function testGetVersion()
    {
        $asset = new Asset(1, 1);
        Assert::equal(1, $asset->getVersion());
    }
}

$testCase = new AssetTest;
$testCase->run();
