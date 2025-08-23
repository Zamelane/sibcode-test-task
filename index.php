<?
define("NEED_AUTH", true);
global $APPLICATION, $USER;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Авторизация");
$APPLICATION->SetAdditionalCSS("/style.css");
?>

<div class="form-center">
    <?php
    // Админа не редиректим, вдруг страницу редактировать надо ...?
    if ($USER->IsAuthorized() && !$USER->IsAdmin()) {
        LocalRedirect('/profile/');
        exit();
    }

    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.form",
        "custom",
        [
            "REGISTER_URL" => "register.php",
            "PROFILE_URL"  => "/profile",
            "SHOW_ERRORS"  => "Y"
        ]
    );
    ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>