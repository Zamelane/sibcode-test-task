<?
global $APPLICATION, $USER;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");

$APPLICATION->SetTitle("Подтверждение регистрации");
$APPLICATION->SetAdditionalCSS("/style.css");
?>

<div class="form-center">
    <?php

    $APPLICATION->IncludeComponent(
        "bitrix:system.auth.confirmation",
        "",
        [
            "AUTH" => "Y",
            "SUCCESS_PAGE" => "/profile/"
        ]
    );
    ?>
</div>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>