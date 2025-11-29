<?php

require_once __DIR__ . "/../../vendor/autoload.php";
require_once __DIR__ . "/src/diag/fileexceptionhandlerlogcustom.php";
require_once __DIR__ . "/src/debug/logger.php"; 
// require_once __DIR__ . "/classes/AbstractIblockPropertyValuesTable.php"; 
// require_once __DIR__ . "/classes/Inter/DoctorPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/AbstractIblockPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/Lists/DoctorPropertyValuesTable.php"; 
require_once __DIR__ . "/src/app/Models/Lists/PocedurePropertyValuesTable.php"; 


require_once __DIR__ . "/src/lib/Dadata.php"; 
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

// API ключ: f0ad26ce162a41c4907caafa144e1c3dd598f148
// скеретный ключ: 0877bb4ccbdf964997920ab4818e40889b91fb81

EventManager::getInstance()->addEventHandler(
    'main',
    'OnProlog',
    [CustomEvents::class, 'OnProlog']
);

// EventManager::getInstance()->AddEventHandler(
//     "main",
//     "OnBeforeProlog",
//     [CustomEvents::class, "OnBeforePrologHandler"]
// );

class CustomEvents
{
    public static function OnProlog()
    {
        global $USER;
        $arJsConfig = array(
            'custom_start' => array(
                'js' => '/local/js/custom/main.js',
                'css' => '',
                'rel' => array()
            )
        );

        foreach ($arJsConfig as $ext => $arExt) {
            \CJSCore::RegisterExt($ext, $arExt);
        }
        CUtil::InitJSCore(array('custom_start'));

        //CJSCore::Init(array('jquery', 'ajax', 'popup'));

        $asset = \Bitrix\Main\Page\Asset::getInstance();

        $settings=[];

       // if (preg_match('/\/crm.*/', GetPagePath())) {
       //    $asset->addString('<script>BX.ready(function () { Dreamsite.crm(' . CUtil::PhpToJSObject($settings) . '); });</script>');
       // }

        if (preg_match('/\/crm\/company\/details\/.*/', GetPagePath())) {
            //$asset->addString('<link rel="stylesheet" type="text/css" href="/local/js/dreamsite/datatables/datatables.min.css"/><script type="text/javascript" src="/local/js/dreamsite/datatables/datatables.min.js"></script>');
           // $asset->addString('<script>BX.ready(function () { Custom.crmCompany(); });</script>');
        }

        //На всех страницах
        // $asset->addString('<script>BX.ready(function () { Dreamsite.all(); });</script>');
    }


    public static function OnBeforePrologHandler()
    {
        CJSCore::Init(array('jquery2'));

    }

}