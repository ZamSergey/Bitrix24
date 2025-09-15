<?php

namespace Bitrix\Main\Diag;

use Psr\Log\LogLevel;

class CustomExeptionHandler extends ExceptionHandlerLog 
{
    const MAX_LOG_SIZE = 1000000;
    const DEFAULT_LOG_FILE = "bitrix/modules/error.log";

    private $level;

    protected $logger;

    public function write($exception, $logType) {
        $text = ExeptionHandlerFormatter::format($exception, false, $this->level);

        $context = [
            'type' => static::logTypeToString($logType),
        ];

        $logLevel = static::logTypeToLevel($logType);

        $message = "{date} - Host: {host} - {type} - {text|\n}";

        $this->logget->log($logLevel, $message, $context);
    }
}
