<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->setTitle('Списки');
use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Otus\Custom\DoctorPropertyValuesTable as DoctorCalss;
Loader::includeModule('iblock');

$iblockId = 16;
$iblockElementId = 32;

// pr(DoctorCalss::getMap());

$elements = \Bitrix\Iblock\Elements\ElementDoctorsTable::getList([ // car - cимвольный код API инфоблока
    'select' => ['FIO','SURNAME','NAME'], // имя свойства 
])->fetchCollection();


pr($elements->getMap());
foreach ($elements as $element) {
    pr($element->getFio()->getValue());
    pr($element->getName());
    pr($element->getId());
}



?>
<h1>Тестовая страница</h1>
   
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>