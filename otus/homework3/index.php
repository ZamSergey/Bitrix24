<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->setTitle('Списки');
$APPLICATION->SetAdditionalCSS('/otus/homework3/style.css');
use Bitrix\Main\Loader;
use Bitrix\Iblock\Iblock;
use Otus\Custom\DoctorPropertyValuesTable as DoctorCalss;
use Otus\Custom\PocedurePropertyValuesTable as PocedureCalss;
Loader::includeModule('iblock');

// $iblockId = 16;
// $iblockElementId = 32;
// $doctors = \Bitrix\Iblock\Elements\ElementDoctorsTable::query() 
// ->setSelect([  
//     'NAME',    
//     'CODE',
//     'PROCEDURES_MUL.ELEMENT.NAME'
   
// ])
// ->fetchCollection();

$docs2 = []; 
$doctors = DoctorCalss::query() 
->setSelect([    
   
    'NAME' => 'ELEMENT.NAME',
    'CODE' => 'ELEMENT.CODE',
   //  'PROCEDURES_MUL',
    // 'ID' => 'ELEMENT.ID'
])
->fetchAll();
foreach ($doctors as $doctor) {    
    $docs2[] = $doctor;
}

// затем обходим коллекцию и получаем процедуры
$i = 0;
$path = trim($_GET['path'], '/');
// echo "PATH->".strlen($path);
$separatePath = explode('/', $path);

// print_r($separatePath );

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
?>
<section class="doctors">
<?php if(strlen($path) == 0 && count($separatePath ) == 1) :?>
  
   <div class="cards-list">
      <?
      
      foreach($docs2 as $doc) {
         echo "<div class='card'><a href='/otus/homework3/{$doc['CODE']}'>{$doc['NAME']}</a></div>";
      }

      ?>

   </div>
<? endif ?>
<?php if(strlen($path) > 0 && count($separatePath )== 1) :?>
      
      <?    

         $doctorTest = DoctorCalss::query() 
         ->setSelect([    
            'NAME' => 'ELEMENT.NAME',
            'CODE' => 'ELEMENT.CODE',
            'PROCEDURES_MUL',            
         ])
          ->setFilter( [
            'CODE' => $separatePath[0]
            
         ])
         ->fetchAll();        

      $doc = []; 

      foreach ($doctorTest as $doctor){
         echo "<div class='doctor-page'>";
         echo "<h1>Доктор {$doctor['NAME']}</h1>";
         echo "<p>Процедуры:</p>";           
         
         $procedures = PocedureCalss::query()
            ->setSelect([     
               '*', 
               'NAME' => 'ELEMENT.NAME',
               'ID' => 'ELEMENT.ID'
            ])
            ->setFilter(array('ID' => $doctor['PROCEDURES_MUL']))
            ->fetchAll();

         echo "<ul>";
         foreach($procedures as $prItem) {
            
            echo "<li>{$prItem['NAME']}</li>";
         }
         echo "</ul>";
         echo "</div>";
         $doc[$i][]=$procedures;
         $i++;
      }    
  
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