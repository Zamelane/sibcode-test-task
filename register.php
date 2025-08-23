<?
global $APPLICATION, $USER;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Регистрация");
$APPLICATION->SetAdditionalCSS("/style.css");
?>

<div class="form-center">
    <?php

    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.registration",
        "custom",
        [
            "SUCCESS_PAGE"       => "/profile/",
            "SET_TITLE"          => "N",
        ]
    );
    ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>