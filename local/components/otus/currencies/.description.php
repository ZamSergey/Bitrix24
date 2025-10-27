<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
    'NAME' => GetMessage('CURRENCIES_COMPONENT_NAME'),   
    'DESCRIPTION' => GetMessage('CURRENCIES_COMPONENT_DESCRIPTION'),    
    "PATH" => array(
      "ID" => "otus",
      "CHILD" => array(
         "ID" => "currencies",
         "NAME" => GetMessage('CURRENCIES_COMPONENT_PATH_NAME')
      )
   ),
);