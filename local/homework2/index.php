<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

define('DEBUG_FILE_NAME' , $_SERVER["DOCUMENT_ROOT" ].'/logs/'.date("Y-m-d").'.log');

echo "В log файл ".date("Y-m-d").'.log'." была добавлена новая запись";



$arr = [1,2,3,4,5,6];
Otus\Diag\DebugCustom::writeToFile($arr, "MyTestData");

// Bitrix\Main\Diag\Debug::writeToFile($text,'TEST_VAR');

// writeToLog($text, "Test_log");

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>