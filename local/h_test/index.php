<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->setTitle('Списки');
$APPLICATION->SetAdditionalCSS('/local/h_test/style.css');
use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Otus\Custom\DoctorPropertyValuesTable as DoctorCalss;
use Otus\Custom\PocedurePropertyValuesTable as PocedureCalss;
Loader::includeModule('iblock');

$iblockId = 16;
$iblockElementId = 32;
// $doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::query() 
// ->setSelect([  
//     'NAME',    
//     'CODE',
//     'PROCEDURES_MUL.ELEMENT.NAME'
   
// ])
// ->fetchCollection();

// $doctors = DoctorCalss::query()
//     ->setSelect(['ELEMENT'])
    
//     ->fetchCollection();
    
// $arDoctors = [];
// foreach ($doctors as $doctor){
//     $name = $doctor->getElement()->getName();
    
//     $arDoctors[]=[
//             'NAME' => $name,
//             'CODE' => $doctor->getElement()->getCode()
            
//     ];
// }

// pr($arDoctors );
// $doctorsTableMap = DoctorCalss::getMap();
// pr($doctorsTableMap);
//Эта работает
// $cars = DoctorCalss::query()
//     ->setSelect([
        
//         'SURNAME',
//         'DOCNAME',
//         'PROCEDURES_NAME' => 'PROCEDURES.ELEMENT.NAME',
//     ])    
//     ->registerRuntimeField(
//         null,
//         new \Bitrix\Main\Entity\ReferenceField(
//             'PROCEDURES',
//             \Otus\Custom\PocedurePropertyValuesTable::getEntity(),
//             ['=this.PROCEDURES_MUL' => 'ref.IBLOCK_ELEMENT_ID']
//         )
//     )
//     ->fetchAll();
// pr($cars);

// затем обходим коллекцию и получаем процедуры
$i = 0;
$path = trim($_GET['path'], '/');

pr($path);

// $docs = []; 
// foreach ($doctors as $doctor){
//     $docs[$i]["name"]= $doctor->getName();   
//     $docs[$i]["code"]= $doctor->getCode();     
//     $procedures = [];
//     foreach($doctor->getProceduresMul()->getAll() as $prItem) {
//         $procedures[] = [
//             'name'=> $prItem->getElement()->getName(),                
//             'id' => $prItem->getElement()->getId()
//         ];
//     }
//      $docs[$i][]=$procedures;
//      $i++;
// }

// pr($docs);

//Еще одна попытка сделать запрос

// $elements = DoctorCalss::query() // car - cимвольный код API инфоблока
//     ->addSelect('NAME')   
//     ->addSelect('ID')
//     ->fetchCollection();

// foreach ($elements as $key => $item) {
//     pr($item->getName().' '.$item->getId()); // получение значения свойства MODEL
    
// }

// Добавление записей

// $dbResult = DoctorCalss::add([
//         'FIO'=>'TESTDOCTOR',       
        
        
        
// ]);
// pr($dbResult);
// echo \Bitrix\Iblock\Iblock::wakeUp(16)->getEntityDataClass();

// $result = \Bitrix\Iblock\Elements\ElementDoctorsTable::add([
//     'NAME' => "TESELEMENT" // MANUFACTURER свойства типа «Строка»
// ]);

// if ($result->isSuccess()) {
//     $id = $result->getId();
//     echo "Доктор добавлен, ID: $id";
// } else {
//     $errors = $result->getErrorMessages();
//     echo "Ошибка добавления доктора: " . implode('; ', $errors);
// }


$docs2 = []; 
$doctors = DoctorCalss::query() 
->setSelect([    
   
    'NAME' => 'ELEMENT.NAME',
    'CODE' => 'ELEMENT.CODE',
    'PROCEDURES_MUL',
    'ID' => 'ELEMENT.ID'
])
 ->setFilter( [
             'CODE' => 'strugatskiy-o-e'           
         ])
->fetchAll();

// foreach ($doctors as $doctor) {    
//     $docs2[] = $doctor;
//     $proc_ids = $doctor['PROCEDURES_MUL'];
//     $procedures = PocedureCalss::query()
//         ->setSelect([     
           
//             'NAME' => 'ELEMENT.NAME',
//             'ID' => 'ELEMENT.ID'
//         ])
//         ->setFilter(array('ID' => $proc_ids))
//         ->fetchAll();

//     foreach ($procedures as $procedure) {    
//         pr($procedure);
//     }

// }
pr($doctors);
// $proc_ids = ['35','33'];
$procedures = PocedureCalss::query()
->setSelect([     
    '*', 
    'NAME' => 'ELEMENT.NAME',
    'ID' => 'ELEMENT.ID'
])
->setFilter(array('ID' => $proc_ids))
->fetchAll();

// foreach ($procedures as $procedure) {    
//     pr($procedure);
// }

?>
<div class="cards-list">
    <?
    // foreach($docs as $docs) {
    //     echo "<div class='card'><a href='/local/h_test/{$docs['code']}'>{$docs['name']}</a></div>";
    // }

    ?>

</div>


<!-- <a href="./test">Test</a> -->

<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>