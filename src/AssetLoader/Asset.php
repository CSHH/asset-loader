<?php

namespace HeavenProject\AssetLoader;

use Nette;

class Asset extends Nette\Object
{
    /** @var int */
    private $timestamp;

    /** @var int */
    private $version;

    /**
     * @param int $timestamp
     * @param int $version
     */
    public function __construct($timestamp, $version)
    {
        $this->timestamp = $timestamp;
        $this->version   = $version;
    }

    /**
     * @param int $timestamp
     */
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return int
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    /**
     * @param int $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }

    /**
     * @return int
     */
    public function getVersion()
    {
        return $this->version;
    }
}
