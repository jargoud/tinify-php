<?php

namespace Tinify;

class Result extends ResultMeta
{
    /**
     * @var string
     */
    protected $data;

    public function __construct(array $meta, string $data)
    {
        parent::__construct($meta);

        $this->data = $data;
    }

    public function data(): string
    {
        return $this->data;
    }

    /**
     * @param string $path
     * @return false|int
     */
    public function toFile(string $path)
    {
        return file_put_contents($path, $this->toBuffer());
    }

    public function toBuffer(): string
    {
        return $this->data;
    }

    public function size(): int
    {
        return intval($this->meta["content-length"]);
    }

    public function contentType(): string
    {
        return $this->mediaType();
    }

    public function mediaType(): string
    {
        return $this->meta["content-type"];
    }
}
