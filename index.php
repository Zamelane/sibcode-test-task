<?
define("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

if (isset($_REQUEST["backurl"]) && strlen($_REQUEST["backurl"])>0)
    LocalRedirect($backurl);

$APPLICATION->SetTitle("Авторизация");
?>

<?php
// Админа не редиректим, вдруг страницу редактировать надо ...?
if ($USER->IsAuthorized() && !$USER->IsAdmin()) {
    LocalRedirect('/profile/');
    exit();
}
?>

<p>Вы зарегистрированы и успешно авторизовались.</p>
<p>Используйте административную панель в верхней части экрана для быстрого доступа к функциям управления структурой и информационным наполнением сайта. Набор кнопок верхней панели отличается для различных разделов сайта. Так отдельные наборы действий предусмотрены для управления статическим содержимым страниц, динамическими публикациями (новостями, каталогом, фотогалереей) и т.п.</p>
<p><a href="<?=SITE_DIR?>profile">Перейти в свой профиль</a></p>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>