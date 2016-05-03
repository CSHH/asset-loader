<?php

namespace HeavenProject\AssetLoader;

use Nette;

class AssetUrlComposer extends Nette\Object
{
    /** @var Storage */
    private $storage;

    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param  string $assetUrl
     * @return string
     */
    public function getAssetUrl($assetUrl)
    {
        $asset = $this->storage->getAsset($assetUrl);

        if ($asset) {
            return $assetUrl . '?v=' . $asset->version;
        } else {
            return $assetUrl;
        }
    }
}
