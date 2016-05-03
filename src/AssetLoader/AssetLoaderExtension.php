<?php

namespace HeavenProject\AssetLoader;

use Nette\DI\CompilerExtension;

/**
 * Compiler extension for asset loader.
 */
class AssetLoaderExtension extends CompilerExtension
{
    /** @var array */
    private $defaults = [
        'publicDir' => null,
        'targetDir' => null,
    ];

    public function loadConfiguration()
    {
        $config = $this->getConfig($this->defaults);
        $msg    = "Parameter '%s' was not set.";

        if (empty($config['publicDir'])) {
            throw new AssetLoaderExtensionException(sprintf($msg, 'publicDir'));
        }
        if (empty($config['targetDir'])) {
            throw new AssetLoaderExtensionException(sprintf($msg, 'targetDir'));
        }

        $builder = $this->getContainerBuilder();
        $builder->addDefinition($this->prefix('storage'))
            ->setClass(
                'HeavenProject\AssetLoader\Storage',
                [
                    'publicDir' => $config['publicDir'],
                    'targetDir' => $config['targetDir'],
                ]
            );
        $builder->addDefinition($this->prefix('assetUrlComposer'))
            ->setClass('HeavenProject\AssetLoader\AssetUrlComposer', ['@' . $this->prefix('storage')]);
    }

    public function beforeCompile()
    {
        $builder = $this->getContainerBuilder();
        $builder->getDefinition('latte.latteFactory')
            ->addSetup('?->onCompile[] = function($engine) { HeavenProject\AssetLoader\AssetMacro::install($engine->getCompiler()); }', ['@self']);
    }
}
