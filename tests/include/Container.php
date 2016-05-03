<?php

namespace HeavenProject\Tests;

use Nette;

class Container extends Nette\Object
{
    /**
     * @param  string             $tmpDir
     * @param  string             $wwwDir
     * @param  array              $otherConfigs
     * @return Nette\DI\Container
     */
    public static function createContainer($tmpDir, $wwwDir = null, array $otherConfigs = array())
    {
        @mkdir($tmpDir, 0777);

        $config = new Nette\Configurator;

        if ($wwwDir) {
            $config->addParameters(['wwwDir' => __DIR__ . '/../public']);
        }

        $config->setTempDirectory($tmpDir);
        $config->addConfig(__DIR__ . '/../config/ext.neon');

        foreach ($otherConfigs as $conf) {
            $config->addConfig($conf);
        }

        return $config->createContainer();
    }
}
