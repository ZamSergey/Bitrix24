<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

/** @var array $arResult */
?>
<div class="currency-component">
    <h2>Курс валют</h2>
    <div class="currency-item">
        <span><?= $arResult['CURRANCIES']['BASE'] ?></span>
        <br/>
        <span><?= $arResult['CURRANCIES']['CURRENT'] ?></span>
    </div>
    
</div>