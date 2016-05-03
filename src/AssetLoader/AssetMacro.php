<?php

namespace HeavenProject\AssetLoader;

use Latte\Compiler;
use Latte\MacroNode;
use Latte\Macros\MacroSet;
use Latte\PhpWriter;

/**
 * Loads public asset files (e.g. styles, scripts, images, ...) and appends a version number at the end of its url.
 *
 * Usage is {asset '/path/to/file'}
 */
class AssetMacro extends MacroSet
{
    /**
     * @param Compiler $compiler
     */
    public static function install(Compiler $compiler)
    {
        $me = new static($compiler);
        $me->addMacro('asset', [$me, 'macroAsset']);
    }

    /**
     * @param  MacroNode $node
     * @param  PhpWriter $writer
     * @return string
     */
    public function macroAsset(MacroNode $node, PhpWriter $writer)
    {
        return $writer->write('echo $presenter->context->getByType("HeavenProject\AssetLoader\AssetUrlComposer")->getAssetUrl(%node.args);');
    }
}
