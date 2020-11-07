<?php

namespace Tinify;

use GuzzleHttp\Exception\GuzzleException;

const VERSION = "2.0.0";

class Tinify
{
    /**
     * @var string
     */
    protected static $key = null;

    /**
     * @var int
     */
    protected static $compressionCount = null;

    /**
     * @var Client
     */
    protected static $client = null;

    public static function getKey(): string
    {
        return self::$key;
    }

    public static function setKey(string $key): void
    {
        self::$key = $key;
        self::$client = null;
    }

    public static function getCompressionCount(): int
    {
        return self::$compressionCount;
    }

    public static function setCompressionCount(int $compressionCount): void
    {
        self::$compressionCount = $compressionCount;
    }

    /**
     * @return Client|null
     * @throws AccountException
     */
    public static function getClient(): ?Client
    {
        if (!self::$key) {
            throw new AccountException("Provide an API key with Tinify::setKey(...)");
        }

        if (!self::$client) {
            self::$client = new Client(self::$key);
        }

        return self::$client;
    }

    /**
     * @param string $path
     * @return Source
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromFile(string $path): Source
    {
        return Source::fromFile($path);
    }

    /**
     * @param string $string
     * @return Source
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromBuffer(string $string): Source
    {
        return Source::fromBuffer($string);
    }

    /**
     * @param string $string
     * @return Source
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromUrl(string $string): Source
    {
        return Source::fromUrl($string);
    }
}
