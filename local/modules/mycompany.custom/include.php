<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

/*
 * Здесь размещается код, выполняемый каждый раз при подключении этого модуля
 */

require_once __DIR__ . "/functions.php";
require_once __DIR__ . "/constants.php";

$eventManager = \Bitrix\Main\EventManager::getInstance();

$eventManager->addEventHandler('main', 'OnUserRegister', [
    'MyCompany\Custom\EventHandlers\User',
    'onUserRegister'
]);

//$eventManager->addEventHandler('main', 'OnAfterUserAdd', [
//    'MyCompany\Custom\EventHandlers\Balance',
//    'OnAfterUserAddHandler'
//]);
