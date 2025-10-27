<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->setTitle('Создание своих таблиц БД и написание модели данных к ним');
$APPLICATION->SetAdditionalCSS('/otus/homework3/style.css');
use Bitrix\Main\Loader,
    Bitrix\Main\Type\Date;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\ORM\Fields\Relations\ManyToMany;
use Bitrix\Iblock\Iblock;
use Otus\Custom\DoctorPropertyValuesTable as DoctorCalss;
use Otus\Custom\PocedurePropertyValuesTable as PocedureCalss;
use Otus\Orm\ApartmentcomplexTable as Apartment;
use Otus\Orm\ColorTable;

Loader::includeModule('iblock');

$query = new Query(Apartment::class);
$query->setSelect([
      'ID',
      'TITLE',
      'FLATS',
      'BUILD_DATE',
      'DESCRIPTION',
      'DEVELOPER_ID',
      'DEVELOPER_TOWN' => 'DEVELOPER.TOWN',
      'DEVELOPER_REC' => 'DEVELOPER.REC',
      'DEVELOPER_NAME' => 'DEVELOPER.ELEMENT.NAME',
      'DEVELOPER_CODE' => 'DEVELOPER.ELEMENT.CODE',
      'COLOR_APARTMENT' => 'COLORS.COL_NAME',
      'COLOR_CODE' => 'COLORS.COL_CODE',
        
]);

$apartResult = $query->exec();
$apartments = [];
while ($apartment = $apartResult->fetch()) {
    $apartmentId = $apartment['ID'];
    
    if (!isset($apartments[$apartmentId])) {
        $apartments[$apartmentId] = [
            'ID' => $apartmentId,
            'TITLE' => $apartment['TITLE'],
            'DEVELOPER_ID' => $apartment['DEVELOPER_ID'],
            'DEVELOPER_NAME' => $apartment['DEVELOPER_NAME'],
            'BUILD_DATE' => $apartment['BUILD_DATE']->format('d.m.Y'),
            'DESCRIPTION' => $apartment['DESCRIPTION'],
            'COLORS' => [],
        ];
    }

    $authorFullName = trim(sprintf('%s %s',
        $apartment['COLOR_APARTMENT'] ?? '',
        $apartment['COLOR_CODE'] ?? ''
       
    ));

    if (!empty($authorFullName) && !in_array($authorFullName, $apartments[$apartmentId]['COLORS'])) {
        $apartments[$apartmentId]['COLORS'][] = $authorFullName;
    }

}

dump($apartments);

?>


<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>