<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->setTitle("Создание собственного компонента");
?><h1><?$APPLICATION->IncludeComponent(
	"otus:currencies",
	"",
	Array(
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"SELECTED_CURRENCIES" => "USD",
		"SHOW_BASE_CURRENCY" => "Y"
	)
);?></h1>
<h1> </h1>
<h1></h1>
<h1></h1><?

$arCurrencies = array();
$by = 'sort';
$order = 'asc';
$rsCurrencies = CCurrency::GetList($by, $order);
while ($arCurrency = $rsCurrencies->Fetch()) {
    $arCurrencies[] = ['FULL_NAME' => $arCurrency['FULL_NAME'], 'AMOUNT' => $arCurrency['AMOUNT']];
   
}

// Получаем базовую валюту
// $baseCurrency = CCurrency::GetBaseCurrency();

// pr($arCurrencies);
// pr($baseCurrency);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");

?>