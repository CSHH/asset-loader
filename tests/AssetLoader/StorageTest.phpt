<?php

namespace HeavenProject\Tests\AssetLoader;

use Tester;
use Tester\Assert;
use Mockery as m;
use phpmock\mockery\PHPMockery;
use HeavenProject\AssetLoader\Asset;
use HeavenProject\AssetLoader\Storage;

require __DIR__ . '/../bootstrap.php';

class StorageTest extends Tester\TestCase
{
    protected function tearDown()
    {
        m::close();
    }

    public function testGetNonExistentAsset()
    {
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_exists')->andReturn(false, false);
        m::mock('alias:Nette\Utils\FileSystem')->shouldReceive('createDir')->once()->andReturnNull();
        $storage = new Storage('', '');
        Assert::null($storage->getAsset('/path/to/asset.ext'));
    }

    public function testGetNewAssetAndSaveItInStorage()
    {
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_exists')->andReturn(true, true, false);
        PHPMockery::mock('HeavenProject\AssetLoader', 'filemtime')->andReturn(0);
        PHPMockery::mock('HeavenProject\AssetLoader', 'serialize')->andReturn('');
        m::mock('alias:HeavenProject\Utils\Slugger')->shouldReceive('slugify')->once()->andReturn('');
        m::mock('alias:Nette\Utils\FileSystem')->shouldReceive('write')->once()->andReturnNull();
        $storage = new Storage('', '');
        $asset   = $storage->getAsset('/path/to/asset.ext');
        Assert::equal(0, $asset->timestamp);
        Assert::equal(1, $asset->version);
    }

    public function testGetExistingUnmodifiedAssetFromStorage()
    {
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_exists')->andReturn(true, true, true);
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_get_contents')->andReturn('');
        PHPMockery::mock('HeavenProject\AssetLoader', 'unserialize')->andReturn(new Asset(0, 1));
        PHPMockery::mock('HeavenProject\AssetLoader', 'filemtime')->andReturn(0);
        m::mock('alias:HeavenProject\Utils\Slugger')->shouldReceive('slugify')->once()->andReturn('');
        $storage = new Storage('', '');
        $asset   = $storage->getAsset('/path/to/asset.ext');
        Assert::equal(0, $asset->timestamp);
        Assert::equal(1, $asset->version);
    }

    public function testGetExistingAssetFromStorageAndUpdateIt()
    {
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_exists')->andReturn(true, true, true);
        PHPMockery::mock('HeavenProject\AssetLoader', 'file_get_contents')->andReturn('');
        PHPMockery::mock('HeavenProject\AssetLoader', 'unserialize')->andReturn(new Asset(0, 1));
        PHPMockery::mock('HeavenProject\AssetLoader', 'filemtime')->andReturn(1);
        m::mock('alias:HeavenProject\Utils\Slugger')->shouldReceive('slugify')->once()->andReturn('');
        m::mock('alias:Nette\Utils\FileSystem')->shouldReceive('write')->once()->andReturnNull();
        $storage = new Storage('', '');
        $asset   = $storage->getAsset('/path/to/asset.ext');
        Assert::equal(1, $asset->timestamp);
        Assert::equal(2, $asset->version);
    }
}

$testCase = new StorageTest;
$testCase->run();
