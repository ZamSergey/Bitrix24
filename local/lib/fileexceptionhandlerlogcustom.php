<?php

namespace Local\Lib;


use Bitrix\Main;
use Psr\Log;

class FileExceptionHandlerLogCustom extends ExceptionHandlerLog
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

		$message = " OTUS {date} - Host: {host} - {type} - {$text}\n";

		$this->logger->log($logLevel, $message, $context);
	}


}
