<?php
namespace Otus\Crm\Market;

use Bitrix\Crm\LeadTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use Bitrix\Crm\Service\Container;

class Agents
{
    /**
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function goodsInspection()
    {
        $modules = ['catalog','iblock','crm','im'];
        foreach ($modules as $module) {
            if (!\Bitrix\Main\Loader::includeModule($module)) {
               
            }
        }       
                    

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
            ['ID', 'NAME', 'IBLOCK_ID','PURCHASING_PRICE']
        );

        $totalPrice = 0;
        $products = [];
        while ($element = $res->Fetch()) {
             // Генерируем случайным образом количество товара на складе
            $count = self::getBalance(); 
            // Устанавливаем количество на складе равным 10
            $quantity = $count < 1 ? 10 : $count;
            $quantityReserved = 0; // Можно также сбросить резерв
            // Получаем остатки для каждого товара
            $productInfo = \CCatalogProduct::GetByID($element['ID']);
           
            
            
           
            $totalPrice += $element['PURCHASING_PRICE'] * $quantity;
            $products[] = [
                'ID' => $element['ID'],
                'NAME' => $element['NAME'],
                'QUANTITY' => $quantity,
                'QUANTITY_RESERVED' => $quantityReserved,
                'PRICE' => $element['PURCHASING_PRICE']
            ];
            
            // Обновляем количество товара
            \CCatalogProduct::Update($element['ID'], [
                'QUANTITY' => $quantity,
                'QUANTITY_RESERVED' => $quantityReserved
            ]);

            if($count < 5) {
                    // ID получателя
                $usersId = [5, 6, 7]; // Замените на ID пользователя
                $uniqueTag = 'PROCUREMENT_ORDER_' . $userId . '_' . time() . '_' . rand(1000, 9999);
                foreach ($usersId as $userId) {
                // Отправляем уведомление
                    $arMessageFields = array(
                        "TO_USER_ID" => $userId,
                        "FROM_USER_ID" => 0, // 0 - от системы
                        "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
                        "NOTIFY_MODULE" => "main",
                        "NOTIFY_TAG" => $uniqueTag,                            
                        "NOTIFY_MESSAGE" => "Внимание! Создан заказ на новые запчасти!\n"
                                            . "Закуплено: " . $element['NAME'] . "\n"
                                            . "Дата: " . date('d.m.Y H:i:s'),
                    );
                
                \CIMNotify::Add($arMessageFields);
                }                
            } 
        }        

    
        $entityTypeId = 1040;  
        $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);
       
        if (!$factory) {
            
            // Попробуем получить динамический тип
            $typesMap = \Bitrix\Crm\Service\Container::getInstance()->getDynamicTypesMap();
            
            // Ищем тип
            $type = null;
            foreach ($typesMap->getTypes() as $dynamicType) {
                if ($dynamicType->getEntityTypeId() == $entityTypeId) {
                    $type = $dynamicType;
                    break;
                }
            }
            
            if (!$type) {
                return "Dynamic type $entityTypeId not found in map";
            }
            
            // Снова пытаемся получить фабрику
            $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);
        }

        if ($factory && !empty($products)) {
            $item = $factory->createItem();
            $item->setTitle('Автоматическая закупка товаров');
            $item->setStageId('DT1040_6:UC_O83W0Y');  
            $item->setAssignedById(1); 
            $item->set('OPPORTUNITY', $totalPrice); // Сумма сделки
            $item->set('CURRENCY_ID', 'RUB');  
            
            $productRows = new \Bitrix\Crm\ProductRowCollection();

            foreach ($products as $product) {
                 $productRow1 = (new \Bitrix\Crm\ProductRow())
                ->setProductId($product['ID']) // ID товара из каталога   
                ->setProductName($product['NAME'])
                ->setPrice($product['PRICE'])     
                ->setQuantity(10);                
            
                $productRows->add($productRow1);
            }
            
             $item->setProductRows($productRows);

            $result = $item->save();
            
            if ($result->isSuccess()) {
                return "\Otus\Crm\Market\Agents::goodsInspection();";
                // return "Test;";
            }
        }

        return "\Otus\Crm\Market\Agents::goodsInspection();";
    }

    public static function getBalance()
    {
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
        return $count;
    }
}