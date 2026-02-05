<?php
use Otus\Orm\CarTable;
// use Otus\Crm\Market\Agents;
use Bitrix\Main\Type\Date;



require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

// if (!CModule::IncludeModule("iblock")) {
//     echo "Ошибка: Модуль инфоблоков не установлен";
//     return;
// }

// if (!CModule::IncludeModule("catalog")) {
//     echo "Ошибка: Модуль каталога не установлен";
//     return;
// }


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

// Agents::cleanOldLeads();
\Otus\Crm\Market\Agents::cleanOldLeads();

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';