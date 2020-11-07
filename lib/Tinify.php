<?php

namespace Tinify;

use Tinify\Exception\AccountException;
use Tinify\Exception\ClientException as ClientExceptionAlias;

const VERSION = "1.5.2";

class Tinify
{
    /**
     * @var string
     */
    protected static $key = null;

    /**
     * @var string
     */
    protected static $appIdentifier = null;

    /**
     * @var string
     */
    protected static $proxy = null;

    /**
     * @var int
     */
    protected static $compressionCount = null;

    /**
     * @var Client
     */
    protected static $client = null;

    public static function setKey(string $key): void
    {
        self::$key = $key;
        self::$client = null;
    }

    public static function setAppIdentifier(string $appIdentifier): void
    {
        self::$appIdentifier = $appIdentifier;
        self::$client = null;
    }

    public static function setProxy(string $proxy): void
    {
        self::$proxy = $proxy;
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
     * @throws ClientExceptionAlias
     * @throws Exception\ConnectionException
     */
    public static function getClient(): ?Client
    {
        if (!self::$key) {
            throw new AccountException("Provide an API key with Tinify\setKey(...)");
        }

        if (!self::$client) {
            self::$client = new Client(self::$key, self::$appIdentifier, self::$proxy);
        }

        return self::$client;
    }

    public static function setClient($client)
    {
        self::$client = $client;
    }
}

function setKey(string $key): void
{
    Tinify::setKey($key);
}

function setAppIdentifier(string $appIdentifier): void
{
    Tinify::setAppIdentifier($appIdentifier);
}

function setProxy(string $proxy): void
{
    Tinify::setProxy($proxy);
}

function getCompressionCount(): int
{
    return Tinify::getCompressionCount();
}

function compressionCount(): int
{
    return Tinify::getCompressionCount();
}

/**
 * @param string $path
 * @return Source
 * @throws AccountException
 * @throws Exception\ConnectionException
 * @throws Exception\Exception
 */
function fromFile(string $path): Source
{
    return Source::fromFile($path);
}

/**
 * @param string $string
 * @return Source
 * @throws AccountException
 * @throws Exception\ConnectionException
 * @throws Exception\Exception
 */
function fromBuffer(string $string): Source
{
    return Source::fromBuffer($string);
}

/**
 * @param $string
 * @return Source
 * @throws AccountException
 * @throws Exception\ConnectionException
 * @throws Exception\Exception
 */
function fromUrl(string $string): Source
{
    return Source::fromUrl($string);
}

/**
 * @return bool
 * @throws AccountException
 * @throws Exception\ConnectionException
 * @throws Exception\Exception
 */
function validate(): bool
{
    try {
        Tinify::getClient()->request("post", "/shrink");
    } catch (AccountException $err) {
        if ($err->status == 429) {
            return true;
        }
        throw $err;
    } catch (ClientExceptionAlias $err) {
        return true;
    }
}
