<?php

namespace Tinify;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\RequestOptions;

class Client extends GuzzleClient
{
    public const API_ENDPOINT = "https://api.tinify.com";

    /**
     * @var array
     */
    protected $options;

    public function __construct(string $key)
    {
        parent::__construct([
            'base_uri' => self::API_ENDPOINT,
            RequestOptions::AUTH => ['api', $key],
        ]);
    }
}
