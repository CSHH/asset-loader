<?php

namespace HeavenProject\Tests\AssetLoader;

use Tester;
use Tester\Assert;
use Mockery as m;
use HeavenProject\AssetLoader\Asset;
use HeavenProject\AssetLoader\AssetUrlComposer;

require __DIR__ . '/../bootstrap.php';

class AssetUrlComposerTest extends Tester\TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testGetNonExistentAssetUrl()
    {
        $storage = m::mock('HeavenProject\AssetLoader\Storage');
        $storage->shouldReceive('getAsset')
            ->once()
            ->andReturnNull();

        $composer = new AssetUrlComposer($storage);
        Assert::same('/path/to/asset.ext', $composer->getAssetUrl('/path/to/asset.ext'));
    }

    /**
     * @dataProvider getVersionNumbers
     */
    public function testGetAssetUrlWithVersionNumber($version)
    {
        $storage = m::mock('HeavenProject\AssetLoader\Storage');
        $storage->shouldReceive('getAsset')
            ->once()
            ->andReturn(new Asset(0, $version));

        $composer = new AssetUrlComposer($storage);
        Assert::same('/path/to/asset.ext?v=' . $version, $composer->getAssetUrl('/path/to/asset.ext'));
    }

    /**
     * @return array
     */
    protected function getVersionNumbers()
    {
        return array([1], [2], [3], [4], [5]);
    }
}

$testCase = new AssetUrlComposerTest;
$testCase->run();
