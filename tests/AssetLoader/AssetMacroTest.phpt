<?php

namespace HeavenProject\Tests\AssetLoader;

use HeavenProject\AssetLoader\AssetMacro;
use Latte;
use Nette\Configurator;
use Nette\DI;
use Tester;
use Tester\Assert;

require __DIR__ . '/../bootstrap.php';

class AssetMacroTest extends Tester\TestCase
{
    /**
     * @return DI\Container
     */
    private function createContainer()
    {
        $tmpDir = __DIR__ . '/../tmp/' . getmypid();
        @mkdir($tmpDir, 0777);

        $config = new Configurator;
        $config->setTempDirectory($tmpDir);
        $config->addConfig(__DIR__ . '/../config/config.neon');

        return $config->createContainer();
    }

    public function testAssetMacro()
    {
        $this->createContainer();

        $latteCompiler = new Latte\Compiler;
        AssetMacro::install($latteCompiler);

        Assert::same(
            '<?php echo $presenter->context->getByType("HeavenProject\AssetLoader\AssetUrlComposer")->getAssetUrl("/path/to/asset.ext"); ?>',
            $latteCompiler->expandMacro('asset', '"/path/to/asset.ext"')->openingCode
        );
    }
}

$testCase = new AssetMacroTest;
$testCase->run();
