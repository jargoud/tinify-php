<?php

namespace Tinify;

class ResultMeta
{
    /**
     * @var array
     */
    protected $meta;

    public function __construct(array $meta)
    {
        $this->meta = $meta;
    }

    public function width(): int
    {
        return intval($this->meta["image-width"]);
    }

    public function height(): int
    {
        return intval($this->meta["image-height"]);
    }

    public function location(): ?string
    {
        return $this->meta["location"] ?? null;
    }
}
