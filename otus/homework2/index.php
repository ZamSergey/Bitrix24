<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->setTitle('Отладка и логирование');
// echo "<pre>";
 
//  $a = [1,2,3,4,5,6,7,8,0];
// var_dump($_SERVER);
// dump($a);
// $a = 2 / 0;
//  Bitrix\Main\Diag\Debug::writeToFile($a,'TEST_VAR');
?>
<H1>Домашняя работа 2</H1>
    <p>Часть 1</p>
    <ul>
        <li><a href="/logs/<?php echo date("Y-m-d")?>.log">Файл лога</a></li>
        <li><a href="/local/homework2">Файл генерирующий лог</a></li>
        <li><a href="/bitrix/admin/fileman_admin.php?PAGEN_1=1&SIZEN_1=20&lang=ru&site=s1&path=%2Flocal%2Fphp_interface%2Fsrc%2Fdebug&show_perms_for=0&fu_action=">Файл кастомного класа по генерации лога</a></li>
    </ul>  
    <p>Часть 2</p>
    <ul>
        <li><a href="/local/logs/custom_exeption.log">Файл лога</a></li>
        <li><a href="/debug_example.php">Файл генерирующий лог</a></li>
        <li><a href="/bitrix/admin/fileman_admin.php?lang=ru&path=%2Flocal%2Fphp_interface%2Fsrc%2Fdiag&site=s1">Файл кастомного класа по генерации лога</a></li>
    </ul>  
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>