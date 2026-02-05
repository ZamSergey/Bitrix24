<?php
namespace Otus\Crm\Market;

use Bitrix\Crm\LeadTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;

class Agents
{
    /**
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function cleanOldLeads()
    {
        $modules = ['catalog','iblock'];
        foreach ($modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
               
            }
        }
        // Loader::includeModule('iblock');
        // Loader::includeModule('catalog');
       
        $apiUrl = "https://www.random.org/integers/?num=1&min=0&max=10&col=1&base=10&format=plain&rnd=new";
            
            $context = stream_context_create([
                'http' => [
                    'method' => 'GET',
                    'header' => "User-Agent: Bitrix24-Agent/1.0\r\n",
                    'timeout' => 10,
                    'ignore_errors' => true
                ],
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false
                ]
            ]);                
            $count = @file_get_contents($apiUrl, false, $context);

        //  $count = 5;


        //Получаем товары из раздела
        $res = \CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            [
                'IBLOCK_ID' => 14, // ID инфоблока каталога
                'SECTION_ID' => 17, // ID раздела "Автозапчасти"
                'ACTIVE' => 'Y',
                'INCLUDE_SUBSECTIONS' => 'Y' // включая подразделы
            ],
            false,
            false,
            ['ID', 'NAME', 'IBLOCK_ID']
        );

        $products = [];
        while ($element = $res->Fetch()) {
            // Получаем остатки для каждого товара
            $productInfo = \CCatalogProduct::GetByID($element['ID']);
            // Генерируем случайным образом количество товара на складе
            
            
            if ($productInfo && $count < 1) {
                // Устанавливаем количество на складе равным 10
                $quantity = 10;
                $quantityReserved = 0; // Можно также сбросить резерв
                
                // Обновляем количество товара
                \CCatalogProduct::Update($element['ID'], [
                    'QUANTITY' => $quantity,
                    'QUANTITY_RESERVED' => $quantityReserved
                ]);
                
                $products[] = [
                    'ID' => $element['ID'],
                    'NAME' => $element['NAME'],
                    'QUANTITY' => $quantity,
                    'QUANTITY_RESERVED' => $quantityReserved
                ];
            }
        }
        return "\Otus\Crm\Market\Agents::cleanOldLeads();";
    }
}