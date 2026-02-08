<?php
use Otus\Orm\CarTable;
// use Otus\Crm\Market\Agents;
use Bitrix\Main\Type\Date;
use Bitrix\Main\Loader;
use Bitrix\Crm\Model\Dynamic\TypeTable;
use Bitrix\Crm\StatusTable;
use Bitrix\Crm\Service\Container;


require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

if (!CModule::IncludeModule("crm")) {
    echo "Ошибка: Модуль инфоблоков не установлен";
    return;
}

if (!CModule::IncludeModule("iblock")) {
    echo "Ошибка: Модуль каталога не установлен";
    return;
}
// \Bitrix\Main\Loader::includeModule('crm');
// \Bitrix\Main\Loader::includeModule('iblock');

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->SetTitle('Обновление запасов');

// function getCatalogLimit() {
//  $apiUrl = "https://www.random.org/integers/?num=1&min=0&max=10&col=1&base=10&format=plain&rnd=new";
    
//     $context = stream_context_create([
//         'http' => [
//             'method' => 'GET',
//             'header' => "User-Agent: Bitrix24-Agent/1.0\r\n",
//             'timeout' => 10,
//             'ignore_errors' => true
//         ],
//         'ssl' => [
//             'verify_peer' => false,
//             'verify_peer_name' => false
//         ]
//     ]);    
    
//     $response = @file_get_contents($apiUrl, false, $context);

//     return $response;
// }



// // Получаем товары из раздела
// $res = CIBlockElement::GetList(
//     ['SORT' => 'ASC'],
//     [
//         'IBLOCK_ID' => 14, // ID инфоблока каталога
//         'SECTION_ID' => 17, // ID раздела "Автозапчасти"
//         'ACTIVE' => 'Y',
//         'INCLUDE_SUBSECTIONS' => 'Y' // включая подразделы
//     ],
//     false,
//     false,
//     ['ID', 'NAME', 'IBLOCK_ID']
// );

// $products = [];
// while ($element = $res->Fetch()) {
//     // Получаем остатки для каждого товара
//     $productInfo = CCatalogProduct::GetByID($element['ID']);
//     // Генерируем случайным образом количество товара на складе
//     $count = getCatalogLimit();
    
//     if ($productInfo && $count < 10) {
//         // Устанавливаем количество на складе равным 10
//         $quantity = 10;
//         $quantityReserved = 0; // Можно также сбросить резерв
        
//         // Обновляем количество товара
//         CCatalogProduct::Update($element['ID'], [
//             'QUANTITY' => $quantity,
//             'QUANTITY_RESERVED' => $quantityReserved
//         ]);
        
//         $products[] = [
//             'ID' => $element['ID'],
//             'NAME' => $element['NAME'],
//             'QUANTITY' => $quantity,
//             'QUANTITY_RESERVED' => $quantityReserved
//         ];
//     }
// }

// // Вывод результатов
// foreach ($products as $product) {
//     echo "Товар: {$product['NAME']}<br>";
//     echo "Доступно: " . ($product['QUANTITY'] - $product['QUANTITY_RESERVED']) . " шт.<br>";
//     echo "Всего на складе: {$product['QUANTITY']} шт.<br>";
//     echo "Зарезервировано: {$product['QUANTITY_RESERVED']} шт.<br><br>";
// }
// $stages = CCrmStatus::GetStatusList('DEAL_STAGE');
// print_r($stages);



// $entityTypeId = 1040;  
// $factory = \Bitrix\Crm\Service\Container::getInstance()->getFactory($entityTypeId);

// if ($factory) {
//     $item = $factory->createItem();
//     $item->setTitle('Тестовый элемент с товарами');
//     $item->setStageId('DT1040_6:UC_O83W0Y');  
    
//     $item->setAssignedById(1);  
//     $item->set('OPPORTUNITY', 2500.00); // Сумма сделки
//     $item->set('CURRENCY_ID', 'RUB');           
    
//     $productRows = new \Bitrix\Crm\ProductRowCollection();
    
    // // Создаем первый товар (правильный способ)
    // $productRow1 = (new \Bitrix\Crm\ProductRow())
    //     ->setProductId(120) // ID товара из каталога   
    //     ->setPrice(500.00)     
    //     ->setQuantity(4);
        
    
    // $productRows->add($productRow1);
    
    // // Создаем второй товар
    // $productRow2 = (new \Bitrix\Crm\ProductRow())
    //     ->setProductId(119) // ID второго товара       
    //     ->setPrice(500.00) 
    //     ->setQuantity(4);
       
    
    // $productRows->add($productRow2);
    
    // // Устанавливаем коллекцию товаров
    // $item->setProductRows($productRows);
    
//     $result = $item->save();
    
//     if ($result->isSuccess()) {
//         return "\Otus\Crm\Market\Agents::cleanOldLeads();";
//     } else {
//         // Для отладки можно посмотреть ошибки
//         $errors = $result->getErrorMessages();
//         print_r("Ошибки при сохранении: " . implode(', ', $errors));
//     }
// }

//   $res = \CIBlockElement::GetList(
//             ['SORT' => 'ASC'],
//             [
//                 'IBLOCK_ID' => 14, // ID инфоблока каталога
//                 'SECTION_ID' => 17, // ID раздела "Автозапчасти"
//                 'ACTIVE' => 'Y',
//                 'INCLUDE_SUBSECTIONS' => 'Y' // включая подразделы
//             ],
//             false,
//             false,
//             ['ID', 'NAME', 'IBLOCK_ID', 'PURCHASING_PRICE']
//         );

//         $products = [];
//         while ($element = $res->Fetch()) {
//             // Получаем остатки для каждого товара
//             $productInfo = \CCatalogProduct::GetByID($element['ID']);
//             // Генерируем случайным образом количество товара на складе
            
            
//             if ($productInfo ) {
//                 // Устанавливаем количество на складе равным 10
//                 $quantity = 10;
//                 $quantityReserved = 0; // Можно также сбросить резерв
                
//                 // Обновляем количество товара
//                 // \CCatalogProduct::Update($element['ID'], [
//                 //     'QUANTITY' => $quantity,
//                 //     'QUANTITY_RESERVED' => $quantityReserved
//                 // ]);
                
//                 $products[] = [
//                     'ID' => $element['ID'],
//                     'NAME' => $element['NAME'],
//                     'QUANTITY' => $quantity,
//                     'QUANTITY_RESERVED' => $quantityReserved,
//                     'PRICE' => $element['PURCHASING_PRICE']
//                 ];
//             }
//         }
// print_r($products);


$deals = CCrmDeal::GetListEx(
    ['DATE_CREATE' => 'DESC'],
    [
        'UF_CAR_ID' => 1,
        'CHECK_PERMISSORY' => 'N',
        '!STAGE_ID' => 'C1:UC_DW6II2',
    ],
    false,
    false,
    [
        'ID',
        'TITLE',
        'DATE_CREATE',
        'STAGE_ID',        // Это ID стадии (например: 'NEW', 'PREPARATION', 'EXECUTING')
        'ASSIGNED_BY_ID',
        'OPPORTUNITY',
        'UF_CAR_ID'
    ]
);
if ($deals) {
    $count = $deals->SelectedRowsCount();
}

// Возвращаем только число
echo $count;
$resultDeals = [];

while ($deal = $deals->Fetch()) {
    $dealId = $deal['ID'];
    
    // 1. Получаем прикрепленные товары
    $productRows = CCrmProductRow::LoadRows('D', $dealId);
    $products = [];
    
    foreach ($productRows as $productRow) {
        if (!empty($productRow['PRODUCT_NAME']) && !empty($productRow['QUANTITY'])) {
            $products[] = $productRow['PRODUCT_NAME'] . ' '. $productRow['QUANTITY'];
        }
    }
    
    // 2. Получаем информацию об ответственном
    $assignedBy = null;
    if ($deal['ASSIGNED_BY_ID'] > 0) {
        $user = CUser::GetByID($deal['ASSIGNED_BY_ID'])->Fetch();
        if ($user) {
            $assignedBy = [
                'ID' => $user['ID'],
                'FULL_NAME' => trim($user['LAST_NAME'] . ' ' . $user['NAME'] . ' ' . $user['SECOND_NAME']),
                'PROFILE_LINK' => CComponentEngine::MakePathFromTemplate(
                    '/company/personal/user/#user_id#/',
                    ['user_id' => $user['ID']]
                )
            ];
        }
    }
    
    // 3. Получаем название стадии
    $stageId = $deal['STAGE_ID']; // Это ID стадии (строка)
    $stageName = $stageId; // По умолчанию используем ID
    
    // Получаем массив всех стадий с названиями
    // Ключ массива = ID стадии (например: 'NEW')
    // Значение массива = название стадии (например: 'Новая сделка')
    $stageList = CCrmStatus::GetStatusList('DEAL_STAGE');
    
    if (isset($stageList[$stageId])) {
        $stageName = $stageList[$stageId]; // Полное название стадии
    }
    
    // 4. Формируем результат
    $resultDeals[] = [
        'ID' => $dealId,
        'TITLE' => $deal['TITLE'],
        'DATE_CREATE' => $deal['DATE_CREATE'],
        'STAGE_ID' => $stageId,           // ID стадии (например: 'NEW')
        'STAGE_NAME' => $stageName,       // Полное название (например: 'Новая сделка')
        'ASSIGNED_BY' => $assignedBy,
        'OPPORTUNITY' => $deal['OPPORTUNITY'],
        'PRODUCTS' => $products
    ];
}

// Выводим результат
echo '<pre>';
foreach ($resultDeals as $deal) {
    echo "======================\n";
    echo "Название: {$deal['TITLE']}\n";
    echo "Дата создания: {$deal['DATE_CREATE']}\n";
    echo "Стадия: {$deal['STAGE_ID']} ({$deal['STAGE_NAME']})\n";
    echo "Сумма: {$deal['OPPORTUNITY']}\n";
    
    if ($deal['ASSIGNED_BY']) {
        echo "Ответственный: {$deal['ASSIGNED_BY']['FULL_NAME']}\n";
        echo "Ссылка на профиль: {$deal['ASSIGNED_BY']['PROFILE_LINK']}\n";
    }
    
    echo "Товары:\n";
    if (!empty($deal['PRODUCTS'])) {
        foreach ($deal['PRODUCTS'] as $product) {
            echo "  - {$product}\n";
        }
    } else {
        echo "  нет товаров\n";
    }
}
echo '</pre>';
// Agents::cleanOldLeads();
// \Otus\Crm\Market\Agents::cleanOldLeads();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';