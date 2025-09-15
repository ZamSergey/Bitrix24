<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->setTitle('Debug');
echo "<pre>";
 
//  $a = [1,2,3,4,5,6,7,8,0];
// var_dump($_SERVER);
// dump($a);
$a = 2 / 0;
//  Bitrix\Main\Diag\Debug::writeToFile($a,'TEST_VAR');
    

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>