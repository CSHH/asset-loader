<?php

namespace HeavenProject\AssetLoader;

use Nette;
use Nette\Utils\FileSystem;
use HeavenProject\Utils\Slugger;
use HeavenProject\AssetLoader\Asset;

class Storage extends Nette\Object
{
    /** @var string */
    private $publicDir;

    /** @var string */
    private $targetDir;

    /**
     * @param string $publicDir
     * @param string $targetDir
     */
    public function __construct($publicDir, $targetDir)
    {
        $this->publicDir = $publicDir;
        $this->targetDir = $targetDir;

        $this->prepareStorage();
    }

    /**
     * @param  string $assetUrl
     * @return Asset
     */
    public function getAsset($assetUrl)
    {
        if ($this->isExternal($assetUrl)) {
            if ($this->isSameOrigin($assetUrl)) {
                $assetUrl = parse_url($assetUrl, PHP_URL_PATH);
            } else {
                return;
            }
        }

        if (!$this->publicAssetExists($assetUrl)) {
            return;
        }

        $storedFilePath = $this->getStoredAssetFilePath($assetUrl);

        if (!$this->storedAssetExists($storedFilePath)) {
            return $this->createAsset($storedFilePath, $assetUrl);
        }

        $asset    = $this->loadStoredAsset($storedFilePath);
        $currTime = $this->getPublicAssetTimestamp($assetUrl);
        if ($currTime === $asset->timestamp) {
            return $asset;
        } else {
            return $this->updateAsset($storedFilePath, $asset, $currTime);
        }
    }

    /**
     * @param  string $assetUrl
     * @return bool
     */
    private function isExternal($assetUrl)
    {
        $scheme = parse_url($assetUrl, PHP_URL_SCHEME);

        return $scheme === 'http' || $scheme === 'https';
    }

    /**
     * @param  string $assetUrl
     * @return bool
     */
    private function isSameOrigin($assetUrl)
    {
        $url = parse_url($assetUrl);

        $resourceAssetDomain = $url['scheme'] . '://' . $url['host'];
        $originDomain = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'];

        return $resourceAssetDomain === $originDomain;
    }

    /**
     * @param  string $file
     * @param  string $assetUrl
     * @return Asset
     */
    private function createAsset($file, $assetUrl)
    {
        $asset = new Asset($this->getPublicAssetTimestamp($assetUrl), 1);
        $this->save($asset, $file);

        return $asset;
    }

    /**
     * @param string $file
     * @param Asset  $asset
     * @param int    $currentTime
     */
    private function updateAsset($file, $asset, $currentTime)
    {
        $asset->timestamp = $currentTime;
        $asset->version   = $asset->version + 1;
        $this->save($asset, $file);

        return $asset;
    }

    /**
     * @param  string $assetUrl
     * @return bool
     */
    private function publicAssetExists($assetUrl)
    {
        return file_exists($this->publicDir . '/' . $assetUrl);
    }

    /**
     * @param  string $assetUrl
     * @return string
     */
    private function getStoredAssetFilePath($assetUrl)
    {
        return $this->targetDir . '/' . Slugger::slugify($assetUrl);
    }

    /**
     * @param  string $storedFile
     * @return bool
     */
    private function storedAssetExists($storedFile)
    {
        return file_exists($storedFile);
    }

    /**
     * @param  string $assetUrl
     * @return int
     */
    private function getPublicAssetTimestamp($assetUrl)
    {
        return filemtime($this->publicDir . $assetUrl);
    }

    /**
     * @param  string $file
     * @return Asset
     */
    private function loadStoredAsset($file)
    {
        return unserialize(file_get_contents($file));
    }

    /**
     * @param Asset  $asset
     * @param string $file
     */
    private function save(Asset $asset, $file)
    {
        FileSystem::write($file, serialize($asset));
    }

    private function prepareStorage()
    {
        if (!file_exists($this->targetDir)) {
            FileSystem::createDir($this->targetDir);
        }
    }
}
