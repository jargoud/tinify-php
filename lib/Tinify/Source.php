<?php

namespace Tinify;

class Source
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var array
     */
    protected $commands;

    public function __construct(string $url, array $commands = array())
    {
        $this->url = $url;
        $this->commands = $commands;
    }

    /**
     * @param string $path
     * @return static
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public static function fromFile(string $path): self
    {
        return self::fromBuffer(file_get_contents($path));
    }

    /**
     * @param string $string
     * @return static
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public static function fromBuffer(string $string): self
    {
        $response = Tinify::getClient()->request("post", "/shrink", $string);
        return new self($response->headers["location"]);
    }

    /**
     * @param string $url
     * @return static
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public static function fromUrl(string $url): self
    {
        $body = array("source" => array("url" => $url));
        $response = Tinify::getClient()->request("post", "/shrink", $body);
        return new self($response->headers["location"]);
    }

    public function preserve(): self
    {
        $options = $this->flatten(func_get_args());
        $commands = array_merge($this->commands, array("preserve" => $options));
        return new self($this->url, $commands);
    }

    private static function flatten(array $options): array
    {
        $flattened = array();
        foreach ($options as $option) {
            if (is_array($option)) {
                $flattened = array_merge($flattened, $option);
            } else {
                array_push($flattened, $option);
            }
        }
        return $flattened;
    }

    public function resize(array $options): self
    {
        $commands = array_merge($this->commands, array("resize" => $options));
        return new self($this->url, $commands);
    }

    /**
     * @param array $options
     * @return Result
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public function store(array $options): Result
    {
        $response = Tinify::getClient()->request(
            "post",
            $this->url,
            array_merge($this->commands, array("store" => $options))
        );
        return new Result($response->headers, $response->body);
    }

    /**
     * @param string $path
     * @return false|int
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public function toFile(string $path)
    {
        return $this->result()->toFile($path);
    }

    /**
     * @return Result
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public function result(): Result
    {
        $response = Tinify::getClient()->request("get", $this->url, $this->commands);
        return new Result($response->headers, $response->body);
    }

    /**
     * @return mixed
     * @throws Exception\AccountException
     * @throws Exception\ConnectionException
     * @throws Exception\Exception
     */
    public function toBuffer()
    {
        return $this->result()->toBuffer();
    }
}
