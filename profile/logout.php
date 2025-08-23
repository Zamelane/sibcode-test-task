<?php
global $APPLICATION, $USER;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Выход");

$USER->Logout();
LocalRedirect('/');