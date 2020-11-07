<?php

namespace Tests;

use GuzzleHttp\Exception\GuzzleException;
use Tinify\AccountException;
use Tinify\Source;
use Tinify\Tinify;

class TinifyTest extends AbstractTest
{
    /**
     * @throws AccountException
     * @throws GuzzleException
     */
    public function testFromFile(): void
    {
        $this->setApiKey();

        $source = Tinify::fromFile(self::SAMPLE_IMAGE_PATH);

        $this->assertInstanceOf(Source::class, $source);
        $this->assertNotFalse(
            filter_var($source->getUrl(), FILTER_VALIDATE_URL)
        );

        $options = ["copyright", "creation"];
        $source->preserve($options);

        $this->assertEquals(
            ['preserve' => $options],
            $source->getCommands()
        );

        $resultFilePath = __DIR__ . '/images/result.png';
        $source->toFile($resultFilePath);

        $this->assertTrue(
            file_exists($resultFilePath)
        );
    }

    /**
     * @throws AccountException
     * @throws GuzzleException
     */
    public function testFromUrl(): void
    {
        $this->setApiKey();

        $source = Tinify::fromUrl('https://picsum.photos/536/354');

        $this->assertInstanceOf(Source::class, $source);
        $this->assertNotFalse(
            filter_var($source->getUrl(), FILTER_VALIDATE_URL)
        );

        $options = [
            'method' => 'fit',
            'width' => 150,
            'height' => 150,
        ];
        $source->resize($options);

        $this->assertEquals(
            ['resize' => $options],
            $source->getCommands()
        );

        $this->assertNotEmpty($source->toBuffer());
    }
}
