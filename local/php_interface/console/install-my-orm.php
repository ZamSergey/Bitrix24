<?php
if (php_sapi_name() != 'cli')
{
    die();
}

define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS",true);
define("BX_NO_ACCELERATOR_RESET", true);
define("BX_CRONTAB", true);
define("STOP_STATISTICS", true);
define("NO_AGENT_STATISTIC", "Y");
define("DisableEventsCheck", true);
define("NO_AGENT_CHECK", true);

$_SERVER['DOCUMENT_ROOT'] = realpath('/home/bitrix/www');
require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Entity\Base;
use Bitrix\Main\Application;
use Otus\Orm\ApartmentcomplexTable;
use Otus\Orm\ProviderTable;


$entities = [
    ProviderTable::class,
	ApartmentcomplexTable::class
	
    
];

foreach ($entities as $entity) {
    if (!Application::getConnection($entity::getConnectionName())->isTableExists($entity::getTableName())) {
        Base::getInstance($entity)->createDbTable();
    }
}
$connection = Application::getConnection();

$tableName = 'my_apartment_color';

if (!$connection->isTableExists($tableName)) {
    $connection->queryExecute("
		CREATE TABLE {$tableName} (
			APARTMENT_ID int NOT NULL,
			COLOR_ID int NOT NULL,
			PRIMARY KEY (APARTMENT_ID, COLOR_ID)
		)
	");
}