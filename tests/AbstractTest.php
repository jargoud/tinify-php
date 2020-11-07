<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Dotenv\Dotenv;
use Tinify\Tinify;

abstract class AbstractTest extends TestCase
{
    protected const SAMPLE_IMAGE_PATH = __DIR__ . "/images/sample.png";

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadDotenv();
    }

    protected function loadDotenv(): void
    {
        $dotenvFilePath = __DIR__ . '/../.env';

        if (file_exists($dotenvFilePath)) {
            $dotenv = new Dotenv();
            $dotenv->load($dotenvFilePath);
        }
    }

    protected function setApiKey(): void
    {
        Tinify::setKey($this->getApiKey());
    }

    private function getApiKey(): string
    {
        return $_ENV['TINIFY_API_KEY'];
    }
}
