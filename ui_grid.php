<?php

use Otus\Orm\CarTable;

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/header.php';

/**
 * @var CMain $APPLICATION
 */

$APPLICATION->SetTitle('Гараж клиента');
$APPLICATION->IncludeComponent('bitrix:ui.sidepanel.wrapper', '', [
    'POPUP_COMPONENT_NAME' => 'otus:book.grid',
    'POPUP_COMPONENT_TEMPLATE_NAME' => '',
    'POPUP_COMPONENT_PARAMS' => [
        'BOOK_PREFIX' => 'TEST ',
        'ORM_CLASS' => CarTable::class,
    ],
]);

require_once $_SERVER['DOCUMENT_ROOT'] . '/bitrix/footer.php';