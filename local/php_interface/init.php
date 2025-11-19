<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/src/diag/fileexceptionhandlerlogcustom.php";
require_once __DIR__ . "/src/debug/logger.php"; 
// require_once __DIR__ . "/classes/AbstractIblockPropertyValuesTable.php"; 
// require_once __DIR__ . "/classes/Inter/DoctorPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/AbstractIblockPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/Lists/DoctorPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/Lists/PocedurePropertyValuesTable.php"; 
// include_once __DIR__ . '/src/app/autoload.php';


// spl_autoload_register(function ($className) {
//     $baseDir = '/home/bitrix/www/local/php_interface/classes/';
//     $file = $baseDir . str_replace('\\', '/', $className) . '.php';
//     if (file_exists($file)) {
//         require_once $file;
//     }
// });

function pr($var, $type = false) {
    echo '<pre style="font-size:10px; border:1px solid #000; background:#FFF; text-align:left; color:#000;">';
    if ($type)
        var_dump($var);
    else
        print_r($var);
    echo '</pre>';
}

use Bitrix\Main\EventManager;

$eventManager = EventManager::getInstance();

// пользовательский тип для свойства инфоблока
$eventManager->AddEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    [
        'Otus\userTypes\IBLink', // класс обработчик пользовательского типа свойства 
        'GetUserTypeDescription'
    ]
);
// пользовательский тип для свойства инфоблока
$eventManager->AddEventHandler(
    'iblock',
    'OnIBlockPropertyBuildList',
    [
        'Otus\userTypes\CUserTypeOnlineRecord', // класс обработчик пользовательского типа свойства 
        'GetUserTypeDescription'
    ]
);