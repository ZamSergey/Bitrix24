<?php
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$request = Bitrix\Main\Context::getCurrent()->getRequest();

if($request->isPost()) {
    $postData = $request->getPostList()->toArray();

    \Bitrix\Main\Loader::includeModule('iblock');

    $el = new CIBlockElement();

    $newDate = str_replace('T', ' ', $postData['TIME']) . ":00";
    // $newDate = str_replace('-', '.',$newDate);

    $prop = [
        'PROCEDURE_ID' => $postData['PROC_ID'],
        'DATE' => $newDate,
        'PACIENT' => $postData['NAME'],
        'DOCTOR_ID' => $postData['DOCTOR_ID'],
    ];
    $iblockCODE = 'booking';
    $filter = array('CODE' => $iblockCODE, 'ACTIVE' => 'Y');
    
    $iblock = CIBlock::GetList(array('SORT' => 'ASC'), $filter)->Fetch();
    $iblockID =  $iblock ? $iblock['ID'] : null;
    if(!$iblockID) {
        echo 'Iblock' . $iblockCODE  . ' не найден!';
    } 
    $arLoadProductArray = [
        'IBLOCK_ID' => $iblockID,
        'PROPERTY_VALUES' => $prop,
        'NAME' => 'Запись на прием',
        'ACTIVE' => 'Y',
    ];

    if($element_id = $el->Add($arLoadProductArray)) {
        echo 'New IDs: ' . $element_id;
    } else {
        echo 'Error: ' . $el->LAST_ERROR;
    }
}

die();