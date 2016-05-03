<?php

namespace HeavenProject\Tests\AssetLoader;

use Tester;
use Tester\Assert;
use HeavenProject\Tests\Container;

require __DIR__ . '/../bootstrap.php';

class AssetLoaderExtensionTest extends Tester\TestCase
{
    /** @var string */
    private $configDir;

    /** @var string */
    private $tmpDir;

    public function __construct()
    {
        $this->configDir = __DIR__ . '/../config';
    }

    protected function setUp()
    {
        $this->tmpDir = __DIR__ . '/../tmp/' . getmypid();
    }

    public function testGeneratedContainer()
    {
        $container = Container::createContainer(__DIR__ . '/../tmp/' . getmypid());
        $type      = 'HeavenProject\AssetLoader\AssetUrlComposer';

        Assert::type($type, $container->getByType($type));
    }

    public function testGeneratedContainerThrowsExceptionWhenPublicDirParameterIsMissing()
    {
        Assert::exception(function () {
            $configs = [$this->configDir . '/ext-public-dir-not-set.neon'];
            Container::createContainer($this->tmpDir, null, $configs);
        }, 'HeavenProject\AssetLoader\AssetLoaderExtensionException', "Parameter 'publicDir' was not set.");
    }

    public function testGeneratedContainerThrowsExceptionWhenTargetDirParameterIsMissing()
    {
        Assert::exception(function () {
            $configs = [$this->configDir . '/ext-target-dir-not-set.neon'];
            Container::createContainer($this->tmpDir, null, $configs);
        }, 'HeavenProject\AssetLoader\AssetLoaderExtensionException', "Parameter 'targetDir' was not set.");
    }

    public function testGeneratedContainerThrowsExceptionWhenNoParameteSet()
    {
        Assert::exception(function () {
            $configs = [$this->configDir . '/ext-no-parameter-set.neon'];
            Container::createContainer($this->tmpDir, null, $configs);
        }, 'HeavenProject\AssetLoader\AssetLoaderExtensionException', "Parameter 'publicDir' was not set.");
    }
}

$testCase = new AssetLoaderExtensionTest;
$testCase->run();
