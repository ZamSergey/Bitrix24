<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
	return;

if (!CModule::IncludeModule('currency')) {
    ShowError('Модуль currency не установлен');
    return;
}

$arCurrencies = array();
$by = 'sort';
$order = 'asc';
$rsCurrencies = CCurrency::GetList($by, $order);
while ($arCurrency = $rsCurrencies->Fetch()) {
    $arCurrencies[$arCurrency['CURRENCY']] = $arCurrency['CURRENCY'] . ' - ' . $arCurrency['FULL_NAME'];
}

// Получаем базовую валюту
$baseCurrency = CCurrency::GetBaseCurrency();
$baseCurrencyName = $baseCurrency ? CCurrency::GetByID($baseCurrency)['FULL_NAME'] : '';


$arComponentParameters = array(
    'PARAMETERS' => array(
        'CACHE_TIME' => array('DEFAULT' => 3600),
        
        'SELECTED_CURRENCIES' => array(
            'PARENT' => 'BASE',
            'NAME' => 'Текущая валюта',
            'TYPE' => 'LIST',
            // 'MULTIPLE' => 'Y',
            'ADDITIONAL_VALUES' => 'N',
            'VALUES' => $arCurrencies,
            'DEFAULT' => $baseCurrency ? array($baseCurrency) : array(),
            'REFRESH' => 'Y',
        ),
        
        'SHOW_BASE_CURRENCY' => array(
            'PARENT' => 'BASE',
            'NAME' => "Показать базовую валюту",
            'TYPE' => 'CHECKBOX',
            'DEFAULT' => 'Y',
        ),
    ),
);