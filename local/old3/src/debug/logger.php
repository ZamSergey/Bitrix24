<?php

namespace Otus\Diag;

use Bitrix\Main\Diag\FileExceptionHandlerLog;

class DebugCustom extends FileExceptionHandlerLog
{
	protected static $timeLabels = [];


	public static function writeToFile($var, $varName = "", $fileName = "")
	{
        
		if (empty($fileName))
		{
			$fileName = "logs/".date("Y-m-d").".log";
		}

		$data = "";
		if ($varName != "")
		{
			$data .= $varName . ":\n";
		}

		if (is_array($var))
		{
			$data .= print_r($var, true) . "\n";
		}
		else
		{
			$data .= $var . "\n";
		}

		file_put_contents($_SERVER["DOCUMENT_ROOT"] . "/" . $fileName, $data . "\n", FILE_APPEND);
	}
}
