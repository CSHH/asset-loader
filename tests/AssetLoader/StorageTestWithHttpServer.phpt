<?php

namespace HeavenProject\Tests\AssetLoader;

use Tester;
use Tester\Assert;
use HeavenProject\AssetLoader\Storage;
use HeavenProject\Tests\Container;
use Kdyby\TesterExtras\HttpServer;

require __DIR__ . '/../bootstrap.php';

class StorageTestWithHttpServer extends Tester\TestCase
{
    /** @var string */
    private $publicAssetsDir;

    public function __construct()
    {
        // Kdyby\TesterExtras\HttpServer requires this constant to be defined
        define('TEMP_DIR', __DIR__ . '/../tmp/' . getmypid());

        $this->publicAssetsDir = __DIR__ . '/../public';
    }

    public function testGetExternalAsset()
    {
        $container = Container::createContainer(TEMP_DIR, $this->publicAssetsDir);

        $server = new HttpServer();
        $server->start($this->publicAssetsDir . '/.gitkeep');

        $url    = $server->getUrl();
        $scheme = $url->getScheme();
        $host   = $url->getHost();
        $port   = $url->getPort();

        $GLOBALS['_SERVER'] = ['HTTP_HOST' => $host];

        $parameters   = $container->getParameters();
        $assetAddress = $scheme . '://' . $host . ':' . $port . '/test-asset.txt';

        $storage = new Storage($parameters['wwwDir'], $parameters['tempDir'] . '/assets');

        $asset1 = $storage->getAsset($assetAddress);
        Assert::type('HeavenProject\AssetLoader\Asset', $asset1);
        Assert::equal(1, $asset1->version);

        touch($this->publicAssetsDir . '/test-asset.txt');

        $asset2 = $storage->getAsset($assetAddress);
        Assert::type('HeavenProject\AssetLoader\Asset', $asset2);
        Assert::equal(2, $asset2->version);

        $server->slaughter();
    }

    public function testGetExternalAssetNotSameOrigin()
    {
        $container = Container::createContainer(TEMP_DIR);

        $server = new HttpServer();
        $server->start($this->publicAssetsDir . '/.gitkeep');

        $GLOBALS['_SERVER'] = ['HTTP_HOST' => $server->getUrl()->getHost()];

        $parameters = $container->getParameters();

        $storage = new Storage($parameters['wwwDir'], $parameters['tempDir'] . '/assets');
        $asset   = $storage->getAsset('http://example.com/test-asset.txt');
        Assert::null($asset);

        $server->slaughter();
    }
}

$testCase = new StorageTestWithHttpServer;
$testCase->run();
