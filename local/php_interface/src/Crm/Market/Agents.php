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
    public static function cleanOldLeads()
    {
        $modules = ['catalog','iblock','crm'];
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
$entityTypeId = 1040;
      $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);
        print_r($factory);
if (!$factory) {
    echo '!!!!';
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

if ($factory) {
    $item = $factory->createItem();
    $item->setTitle('Тестовый элемент');
    $item->setStageId('DT1040_6:UC_O83W0Y');  
    $item->setAssignedById(1);
    
    // Стадия
    // $stages = $factory->getStages();
    // if ($stages && $stages->getAll()) {
    //     $stageList = $stages->getAll();
    //     $firstStage = reset($stageList);
    //     $item->setStageId($firstStage->getStatusId());
    // }
    
    $result = $item->save();
    
    if ($result->isSuccess()) {
        return "\Otus\Crm\Market\Agents::cleanOldLeads();";
    }
}

        // $entityTypeId = 1040;


        // $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);

        // $item = $factory->createItem();
        // $item->setTitle('Сделка с несколькими товарами');
        // $item->setStageId('DT1040_6:UC_O83W0Y');        
        // $item->setAssignedById(1);

        // $operation = $factory->getAddOperation($item);
        // $operationResult = $operation->launch();

// Код для добавления товаров


// use Bitrix\Crm\Service\Container;
// use Bitrix\Crm\ProductRowTable;
// use Bitrix\Catalog\ProductTable;

// $products = [
//     ['ID' => 123, 'QUANTITY' => 10],
//     ['ID' => 456, 'QUANTITY' => 5],
//     ['ID' => 789, 'QUANTITY' => 3]
// ];

// $factory = Container::getInstance()->getFactory(\CCrmOwnerType::Deal);
// $resultText = '';
// $processedItems = 0;

// if ($factory) {
//     // 1. Создаем объект сделки
//     $item = $factory->createItem();
//     $item->setTitle('Сделка с несколькими товарами');
//     $item->setStageId('NEW');
//     $item->setCurrencyId('RUB');
//     $item->setAssignedById(1);
    
//     $totalAmount = 0;
//     $productRows = [];
    
//     // 2. Подготавливаем товары
//     foreach ($products as $productData) {
//         $productId = $productData['ID'];
//         $quantity = $productData['QUANTITY'];
        
//         // Получаем данные из каталога
//         $catalogProduct = ProductTable::getList([
//             'filter' => ['=ID' => $productId],
//             'select' => ['ID', 'NAME', 'PRICE']
//         ])->fetch();
        
//         if ($catalogProduct) {
//             $price = $catalogProduct['PRICE'] ?: 1000;
//             $productName = $catalogProduct['NAME'];
            
//             // Создаем товарную позицию
//             $productRow = new \Bitrix\Crm\ProductRow();
//             $productRow->setProductId($productId);
//             $productRow->setProductName($productName);
//             $productRow->setPrice($price);
//             $productRow->setQuantity($quantity);
//             $productRow->setTaxRate(20.0);
//             $productRow->setTaxIncluded(true);
//             $productRow->setMeasureCode(796);
//             $productRow->setMeasureName('шт.');
            
//             $productRows[] = $productRow;
//             $totalAmount += ($price * $quantity);
//             $processedItems++;
//         }
//     }
    
//     // 3. Добавляем все товары в сделку
//     if (!empty($productRows)) {
//         $item->setProductRows($productRows);
        
//         // 4. Сохраняем сделку
//         $operation = $factory->getAddOperation($item);
//         $operationResult = $operation->launch();
        
//         if ($operationResult->isSuccess()) {
//             $dealId = $item->getId();
            
//             // Формируем результат
//             $resultText = "✅ Создана сделка ID: {$dealId}\n";
//             $resultText .= "💰 Общая сумма: {$totalAmount}\n";
//             $resultText .= "📦 Добавлено товаров: {$processedItems}\n\n";
            
//             foreach ($productRows as $index => $row) {
//                 $number = $index + 1;
//                 $productAmount = $row->getPrice() * $row->getQuantity();
//                 $resultText .= "{$number}. {$row->getProductName()}\n";
//                 $resultText .= "   Кол-во: {$row->getQuantity()} x {$row->getPrice()} = {$productAmount}\n";
//             }
//         } else {
//             $resultText = "❌ Ошибка при создании сделки: " . 
//                          implode(', ', $operationResult->getErrorMessages());
//         }
//     } else {
//         $resultText = "⚠️ Не найдено товаров для добавления";
//     }
    
//     // Возвращаем результат
//     $this->SetVariable('Var1', $resultText);
//     $this->SetVariable('ProcessedItems', $processedItems);
//     $this->SetVariable('DealId', $dealId ?? 0);
//     $this->SetVariable('TotalAmount', $totalAmount);
// }


// Код для добавления товаров







        return "\Otus\Crm\Market\Agents::cleanOldLeads();";
    }
}