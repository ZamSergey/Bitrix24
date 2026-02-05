<?php
use Otus\Orm\CarTable;
use Otus\Orm\GarageTable;
use Bitrix\Main\Type\Date;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->SetTitle('Работа с собственными таблицами - добавление');

$carId = CarTable::add([
    'BRAND' => 'Lada',
    'MODEL' => 'Vesta',
    'CAR_NUMBER' => 'CK718M37',
    'CAR_YEAR' => (new Date)->add('-3Y'),
    'MILEAGE' => 500,
    'COLOR' => 'Чёрный',
])->getId();

$car = CarTable::getList([
    'filter' => [
        'ID' => $carId
    ],
    'order' => [
        'ID' => 'DESC',
    ],
    'limit' => 1,
])->fetchObject();

$garageId = GarageTable::add([
    'TITLE' => 'Гараж клиента',
    'CLIENT_ID' => 1
     
])->getId();
// $garageId = 14;
$garage = GarageTable::getList([
    'filter' => [
        'ID' => $garageId
    ],
    'order' => [
        'ID' => 'DESC',
    ],
    'limit' => 1,
])->fetchObject();

// $car->addToGarage($garage);
// $car->save();

// Используйте это:
$garage->addToCars($car);  // Гараж добавляет к себе машину

$contactId = 1; // ID существующего контакта
// Устанавливаем ID клиента
$garage->setClientId($contactId);
$garage->save();

echo "Клиент добавлен к гаражу<br>";
$garage->save();

// Или установите связь через поле:
$car->setGarageId($garage->getId());
$car->save();
// Явно загружаем связанного клиента
$garage->fill(['CLIENT']);
// Проверяем, есть ли клиент у гаража
// if ($garage->getClientId() > 0) {
//     // Получаем контакт
//     $client = $garage->getClient();
    
//     if ($client) {
//         // Формируем ФИО
//         $nameParts = [];
//         if ($client->getLastName()) {
//             $nameParts[] = $client->getLastName();
//         }
//         if ($client->getName()) {
//             $nameParts[] = $client->getName();
//         }
//         if ($client->getSecondName()) {
//             $nameParts[] = $client->getSecondName();
//         }
        
//         $fullName = implode(' ', $nameParts);
        
//         // Если ФИО пустое, берем название
//         if (empty($fullName)) {
//             $fullName = $client->getTitle() ?: 'Без имени';
//         }
        
//         echo sprintf(
//             '🚗 Гараж "%s" принадлежит клиенту: <strong>%s</strong> (ID: %d)',
//             $garage->getTitle(),
//             $fullName,
//             $client->getId()
//         );
//     }
// } else {
//     echo 'Гараж не привязан к клиенту';
// }
$client = $garage->getClient();
echo sprintf(
    'Брэнд: %s; Модель: %s; Номер: %s; Пробег: %s; Гараж: %s<br>; Клиент: %s %s<br>',
    $car->getBrand(),
    $car->getModel(),
    $car->getCarNumber(),
    $car->getMileage(),
    $garage->getTitle(),
    $client->getName(),
    $client->getLastName(),

);



$garageCars = $garage->getCars();
foreach ($garageCars as $garageCar) {
    $firstGarageCar = $garageCar;
    break;
}

$garageCar = $firstGarageCar->getBrand() . ' ' . $firstGarageCar->getModel();
echo sprintf(
    'Модель и бренд авто: %s<br>',
    $garageCar,
    
);


//  $cars = CarTable::getList([
        
//         'select' => ['*', 'GARAGE'], // Выбираем все поля + связанный объект GARAGE        
//         'order' => ['ID' => 'ASC'], // Сортировка по ID
//     ])->fetchAll();
//    if (empty($cars)) {
//         echo "<tr><td colspan='7'>Автомобили не найдены</td></tr>";
//     } else {
//         foreach ($cars as $car) {
//             echo "<tr>";
//             echo "<td>" . $car['ID'] . "</td>";
//             echo "<td>" . htmlspecialchars($car['BRAND']) . "</td>";
//             echo "<td>" . htmlspecialchars($car['MODEL']) . "</td>";
//             echo "<td>" . htmlspecialchars($car['CAR_NUMBER']) . "</td>";
//             echo "<td>" . ($car['CAR_YEAR'] ?: '-') . "</td>";
//             echo "<td>" . ($car['MILEAGE'] ?: '-') . "</td>";
//             echo "<td>";
            
//             // Выводим информацию о гараже, если она есть
//             if (isset($car['GARAGE']) && $car['GARAGE']) {
//                 echo "Гараж #" . $car['GARAGE']['ID']; // Предполагаем, что у GarageTable есть поле ID
//                 // Если у гаража есть название или другие поля, добавьте их здесь
//                 // echo " (" . htmlspecialchars($car['GARAGE']['NAME']) . ")";
//             } else {
//                 echo "-";
//             }
            
//             echo "</td>";
//             echo "</tr>";
//         }
//     }

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';