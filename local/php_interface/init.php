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


// ДЗ по событиям

$eventManager->AddEventHandler(
    'crm',
    'OnAfterCrmDealUpdate',
    'TestHandler'
);


function TestHandler ($arFields) {
     // Проверяем, что есть ID сделки
    if (empty($arFields['ID'])) {
        return;
    }
    
    $dealId = $arFields['ID'];
    $summ = $arFields['OPPORTUNITY'];
    $val = $arFields['ACCOUNT_CURRENCY_ID'];
    // Получаем данные сделки
    $deal = CCrmDeal::GetByID($dealId);
    if (!$deal) {
        return;
    }

    $iblockId = 24; // ID инфоблока Test    

    

    Otus\Diag\DebugCustom::writeToFile( $deal, "Данные сделки");

     
    $elementId = FindIBlockElementByDealId($dealId, $iblockId);
    

    if ($elementId['ID'] && $elementId['SUMM'] !==  "$summ|$val") {
        // Обновляем элемент

        $elementFields = array(
            'PROPERTY_VALUES' => array(
                'SUMM' => $summ. '|'.$val,
                'DEALID' => $dealId // Код свойства "Сумма сделки"
            )
        );
        
        $el = new CIBlockElement;
        $result = $el->Update($elementId['ID'], $elementFields);
        if ( $result) {
            Otus\Diag\DebugCustom::writeToFile(  $elementId, "Произошла запись");
        }
        
        if (!$result) {
            // Логируем ошибку, если есть          
            Otus\Diag\DebugCustom::writeToFile( $el->LAST_ERROR, "MyTestData");
        }
    }

}

function FindIBlockElementByDealId($dealId, $iblockId) {
    CModule::IncludeModule('iblock');    
  

    $elements = CIBlockElement::GetList(
        [
            'ID' => 'ASC',
        ],
        [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'PROPERTY_DEALID' => $dealId,
        ],
        false,
        false,
        [
            '*',
        ]
    );

   $elemID = false;
    
    while ($item = $elements->GetNextElement()) {
        $element = $item->GetFields();
        $element['PROPERTIES'] = $item->GetProperties();       
        $elemID['ID'] =  $element['ID'];
        $elemID['SUMM'] =   trim($element['PROPERTIES']['SUMM']['VALUE']);       
    }

    return $elemID;


}


$eventManager->AddEventHandler(
    'iblock',
    'OnAfterIBlockElementUpdate',
    'iblockUpdateHandler'
);

function iblockUpdateHandler ($arFields) { 
    // Отслеживаем изменения только в нужном нам инфоблоке
    if(empty($arFields['ID']) || $arFields['IBLOCK_ID'] != 24) {
        return;
    }
       $elements = CIBlockElement::GetList(
        [
            'ID' => 'ASC',
        ],
        [
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
            'ID' => $arFields['ID'],
        ],
        false,
        false,
        [
            '*',
        ]
    );

    $summ = '';
    $dealID = false;
    while ($item = $elements->GetNextElement()) {
        $element = $item->GetFields();
        $element['PROPERTIES'] = $item->GetProperties();    
      
       $summ =   trim($element['PROPERTIES']['SUMM']['VALUE']);       
       $dealID =   trim($element['PROPERTIES']['DEALID']['VALUE']);       
    }
    
    
    Otus\Diag\DebugCustom::writeToFile(   $dealID, "Получили событие");
    Otus\Diag\DebugCustom::writeToFile(   $arFields, "ПОЛЯ");
    $deal = CCrmDeal::GetByID(intval($dealID));
    if (!$deal) {
        return;
    }
    Otus\Diag\DebugCustom::writeToFile(   $dealID, "Сделку нашли");

    $dealAmount = $deal['OPPORTUNITY'];
    $dealCurrency = $deal['CURRENCY_ID'];

    // Приводим сумму к виду 1200|RUB
    $formatedSumm = floatval($dealAmount) . "|" . $dealCurrency;  

    // Обновляем сделку, только если суммы разные
    if($summ !== $formatedSumm) {
        Otus\Diag\DebugCustom::writeToFile(   $entityFields, "Завшли в условие записи");
        $splitIblockSumm  = explode('|',$summ);
        $bCheckRight = true;

       
        $entityId = $dealID;
        
        $entityFields = [
           
            'OPPORTUNITY'   => $splitIblockSumm[0],
            'CURRENCY_ID'  => $splitIblockSumm[1]
        ];

         Otus\Diag\DebugCustom::writeToFile(   $entityFields, "ЧТО ПИШЕМ");

        $entityObject = new \CCrmDeal( $bCheckRight );

        $isUpdateSuccess = $entityObject->Update(
            $entityId,
            $entityFields,
            $bCompare = true,
            $bUpdateSearch = true,
            $arOptions = [        
                'CURRENT_USER' => \CCrmSecurityHelper::GetCurrentUserID(),        
                // 'IS_SYSTEM_ACTION' => false,       
                // 'REGISTER_SONET_EVENT' => true,
                // 'ENABLE_SYSTEM_EVENTS' => true,        
                // 'SYNCHRONIZE_STAGE_SEMANTICS' => true,
                // 'DISABLE_USER_FIELD_CHECK' => false,       
                // 'DISABLE_REQUIRED_USER_FIELD_CHECK' => false,
            ]
        );

        if ( !$isUpdateSuccess )
        {
            /**
             * Произошла ошибка при обновлении элемента, посмотреть ее можно
             * через любой из способов ниже:
             * 1. $entityFields['RESULT_MESSAGE']
             * 2. $entityObject->LAST_ERROR
             */
            Otus\Diag\DebugCustom::writeToFile(  $entityFields['RESULT_MESSAGE'], "ОШИБКА");
        }
    }   
    
    Otus\Diag\DebugCustom::writeToFile(  $summ, "iBlock");
    Otus\Diag\DebugCustom::writeToFile(  $formatedSumm, "из Сделки");  

    
    // Otus\Diag\DebugCustom::writeToFile( $deal, "Данные сделки");
    // Otus\Diag\DebugCustom::writeToFile( $arFields, "Поля инфоблока");
    // Otus\Diag\DebugCustom::writeToFile(  $summ, "Поля инфоблока");    

}


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