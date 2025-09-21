<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->setTitle('Списки');
$APPLICATION->SetAdditionalCSS('/otus/homework3/style.css');
use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Otus\Custom\DoctorPropertyValuesTable as DoctorCalss;
Loader::includeModule('iblock');

$iblockId = 16;
$iblockElementId = 32;
$doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::query() 
->setSelect([  
    'NAME',    
    'CODE',
    'PROCEDURES_MUL.ELEMENT.NAME'
   
])
->fetchCollection();

// $doctors = DoctorCalss::query() 
// ->setSelect([ 
    
//     'FIO'
   
// ])

// ->fetchAll();
// pr($doctors);

// затем обходим коллекцию и получаем процедуры
$i = 0;
$path = trim($_GET['path'], '/');
// echo "PATH->".strlen($path);
$separatePath = explode('/', $path);

// print_r($separatePath );

$docs = []; 
foreach ($doctors as $doctor){
    $docs[$i]["name"]= $doctor->getName();   
    $docs[$i]["code"]= $doctor->getCode();     
    $procedures = [];
    foreach($doctor->getProceduresMul()->getAll() as $prItem) {
        $procedures[] = [
            'name'=> $prItem->getElement()->getName(),                
            'id' => $prItem->getElement()->getId()
        ];
    }
     $docs[$i][]=$procedures;
     $i++;
}

// pr($docs);
?>
<section class="doctors">
<?php if(strlen($path) == 0 && count($separatePath ) == 1) :?>
  
   <div class="cards-list">
      <?
      echo count($separatePath );
      foreach($docs as $docs) {
         echo "<div class='card'><a href='/otus/homework3/{$docs['code']}'>{$docs['name']}</a></div>";
      }

      ?>

   </div>
<? endif ?>
<?php if(strlen($path) > 0 && count($separatePath )== 1) :?>
      
      <?     
      $doctors1 = \Bitrix\Iblock\Elements\ElementDoctorsTable::query() 
         ->setSelect([ 
            'ID', 
            'NAME',    
            'CODE',
            'PROCEDURES_MUL.ELEMENT.NAME'
            
         ])
         ->setFilter( [
            'CODE' => $separatePath[0],
            'ACTIVE' => 'Y',
         ])
         ->fetchCollection();

      $doc = []; 

      foreach ($doctors1 as $doctor){
         echo "<div class='doctor-page'>";
         echo "<h1>Доктор {$doctor->getName()}</h1>";
         echo "<p>Процедуры:</p>";
         $doc[$i]["name"]= $doctor->getName();   
         $doc[$i]["code"]= $doctor->getCode();     
         $procedures = [];
         echo "<ul>";
         foreach($doctor->getProceduresMul()->getAll() as $prItem) {
            $procedures[] = [
                  'name'=> $prItem->getElement()->getName()               
                  
            ];
            echo "<li>{$prItem->getElement()->getName()}</li>";
         }
         echo "</ul>";
         echo "</div>";
         $doc[$i][]=$procedures;
         $i++;
      }    
   //   pr($doc);
      ?>

   </div>
<? endif ?>
<div class="add-buttons">
   <div class="button"><a  href="/otus/homework3/">Вернуться на главную</a></div>
   
</div>
   </section>

<?

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>