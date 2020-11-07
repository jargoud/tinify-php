<?php

namespace Tinify\Exception;

use Exception as PHPException;

class Exception extends PHPException
{
    /**
     * @var int|null
     */
    public $status;

    public function __construct(string $message, string $type = null, int $status = null)
    {
        $this->status = $status;
        if ($status) {
            parent::__construct($message . " (HTTP " . $status . "/" . $type . ")");
        } else {
            parent::__construct($message);
        }
    }

    public static function create($message, $type, $status): self
    {
        if ($status === 401 || $status === 429) {
            $class = AccountException::class;
        } elseif ($status >= 400 && $status <= 499) {
            $class = ClientException::class;
        } elseif ($status >= 500 && $status <= 599) {
            $class = ServerException::class;
        } else {
            $class = self::class;
        }

        if (empty($message)) {
            $message = "No message was provided";
        }

        return new $class($message, $type, $status);
    }
}
