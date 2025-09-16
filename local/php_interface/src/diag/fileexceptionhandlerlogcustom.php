<?php

namespace Otus\Diag;

use Psr\Log\LogLevel;
use Bitrix\Main\Diag\FileExceptionHandlerLog;
use Bitrix\Main\Diag\ExceptionHandlerFormatter;

class FileExceptionHandlerLogCustom extends FileExceptionHandlerLog
{
	const MAX_LOG_SIZE = 1000000;
	// const DEFAULT_LOG_FILE = "bitrix/modules/error.log";

	private $level;

	/** @var Log\LoggerInterface */
	protected $logger;

	
	public function write($exception, $logType)
	{
		$text = ExceptionHandlerFormatter::format($exception, false, $this->level);

		$context = [
			'type' => static::logTypeToString($logType),
		];

		$logLevel = static::logTypeToLevel($logType);

		$message = "OTUS {date} - Host: {host} - {type} - {$text}\n";

		$this->logger->log($logLevel, $message, $context);
	}


}
