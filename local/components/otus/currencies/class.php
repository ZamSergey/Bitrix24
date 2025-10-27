<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

class CurrencyComponent extends \CBitrixComponent
{
    public function executeComponent()
    {
        $this->arResult = $this->getCurrencies();
        $this->includeComponentTemplate();
    }
    
    private function getCurrencies()
    {
        // Логика получения курсов валют
        $arCurrencies = array();   
        $baseCurrency = CCurrency::GetBaseCurrency();    
 
        $rsCurrencies = CCurrency::GetList();
        while ($arCurrency = $rsCurrencies->Fetch()) {
            if ($arCurrency['CURRENCY'] === $this->arParams['SELECTED_CURRENCIES']) {
                $arCurrencies['CURRENT'] = 'Выбранная валюта: ' . $arCurrency['CURRENCY'] . ' - ' . $arCurrency['FULL_NAME'] . '. Курс: ' .  $arCurrency['AMOUNT'];
            }
            if($this->arParams['SHOW_BASE_CURRENCY'] === 'Y' && $arCurrency['CURRENCY'] === $baseCurrency){
                $arCurrencies['BASE'] = 'Базовая валюта: ' . $arCurrency['CURRENCY'] . ' - ' . $arCurrency['FULL_NAME'];
            }
            
            
        }
        return ['CURRANCIES'=> $arCurrencies];
    }
}