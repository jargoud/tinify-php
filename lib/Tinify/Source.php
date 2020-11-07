<?php

namespace Tinify;

use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;

class Source
{
    /**
     * @var string
     */
    protected $url;

    protected $commands = [];

    public function __construct(string $url, array $commands = [])
    {
        $this->url = $url;
        $this->commands = $commands;
    }

    /**
     * @param string $path
     * @return static
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromFile(string $path): self
    {
        return self::fromBuffer(file_get_contents($path));
    }

    /**
     * @param string $string
     * @return static
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromBuffer(string $string): self
    {
        $response = Tinify::getClient()->post(
            "/shrink",
            [
                RequestOptions::BODY => $string,
            ]
        );

        return new self($response->getHeaderLine("location"));
    }

    /**
     * @param string $url
     * @return static
     * @throws AccountException
     * @throws GuzzleException
     */
    public static function fromUrl(string $url): self
    {
        $response = Tinify::getClient()->post(
            "/shrink",
            [
                RequestOptions::JSON => [
                    'source' => [
                        'url' => $url,
                    ],
                ],
            ]
        );

        return new self($response->getHeaderLine("location"));
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }

    public function preserve(array $options): self
    {
        return $this->addCommands('preserve', $options);
//        return new self(
//            $this->url,
//            array_merge(
//                $this->commands,
//                ["preserve" => $this->flatten(func_get_args())]
//            )
//        );
    }

    public function addCommands(string $command, array $options): self
    {
        $this->commands = array_merge(
            $this->commands,
            [$command => $options]
        );

        return $this;
    }

//    protected function flatten(array $options): array
//    {
//        $flattened = array();
//        foreach ($options as $option) {
//            if (is_array($option)) {
//                $flattened = array_merge($flattened, $option);
//            } else {
//                array_push($flattened, $option);
//            }
//        }
//        return $flattened;
//    }

    public function resize(array $options): self
    {
        return $this->addCommands('resize', $options);
//        return new self(
//            $this->url,
//            array_merge(
//                $this->commands,
//                ["resize" => $options]
//            )
//        );
    }

    /**
     * @param array $options
     * @return Result
     * @throws AccountException
     * @throws GuzzleException
     */
    public function store(array $options): Result
    {
        $response = Tinify::getClient()->post(
            $this->url,
            [
                RequestOptions::JSON => array_merge(
                    $this->commands,
                    ["store" => $options]
                ),
            ]
        );

        return new Result($response->getHeaders(), (string)$response->getBody());
    }

    /**
     * @param string $path
     * @return false|int
     * @throws AccountException
     * @throws GuzzleException
     */
    public function toFile(string $path)
    {
        return $this->result()->toFile($path);
    }

    /**
     * @return Result
     * @throws AccountException
     * @throws GuzzleException
     */
    public function result(): Result
    {
        $response = Tinify::getClient()->get(
            $this->url,
            [
                RequestOptions::JSON => $this->commands,
            ]
        );

        return new Result($response->getHeaders(), (string)$response->getBody());
    }

    /**
     * @return string
     * @throws AccountException
     * @throws GuzzleException
     */
    public function toBuffer(): string
    {
        return $this->result()->toBuffer();
    }
}
