<?php
global $APPLICATION, $USER, $IBLOCK_ID;
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");
$APPLICATION->SetAdditionalCSS("/profile/style.css");

if (!$USER->IsAuthorized()) {
    LocalRedirect('/');
}

use \MyCompany\Custom\Classes\Balance;

$balance = Balance::getUserBalanceFromTransactions();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $amount = (int)$_POST['amount'];
    $operationType = $_POST['submit'];

    // Внутренние проверки не пропустят выполнение с ошибочными данными
    Balance::createTransaction($USER->GetID(), $operationType, $amount);

    LocalRedirect($_SERVER["REQUEST_URI"]);
}
?>

<main>
    <header class="pseudo-header">
        <div>
            <h1>Личный кабинет</h1>
            <p class="sm">Здравствуйте, <?=$USER->GetFirstName()?>!</p>
            <p class="sm">Ваш ID: <strong><?=$USER->GetID()?></strong></p>
        </div>
        <div class="header-right">
            <div class="points-container">
                <?=$balance?>
                баллов
            </div>
            <a href="/profile/logout.php">
                <button class="btn">Выйти</button>
            </a>
        </div>

    </header>

    <div class="pseudo-content">

        <section class="operation-container">
            <h2>Операция с баллами</h2>
            <form method="POST" class="flex-h-center">
                <input class="form-item" type="number" id="amount" name="amount" min="1" placeholder="Баллов к начислению" required>
                <button class="btn" type="submit" name="submit" value="credit">Начислить</button>
            </form>
            <form method="POST" class="flex-h-center">
                <input class="form-item" type="number" id="amount" name="amount" min="1" placeholder="Баллов к списанию" required>
                <button class="btn" type="submit" name="submit" value="debit">Списать</button>
            </form>
        </section>

        <section>
            <h2>История операций</h2>
            <?php
            $arrFilter = array(
                "PROPERTY_USER" => $USER->GetID()
            );
            $APPLICATION->IncludeComponent(
                "bitrix:news.list",
                "custom",
                array(
                    "IBLOCK_TYPE" => "transactions",
                    "IBLOCK_ID" => $IBLOCK_ID,
                    "NEWS_COUNT" => "10",
                    "SORT_BY1" => "DATE_CREATE",
                    "SORT_ORDER1" => "DESC",
                    "PAGER_TEMPLATE" => "round",
                    "SET_TITLE" => "N",
                    "DISPLAY_DATE" => "Y",
                    "DISPLAY_NAME" => "Y",
                    "DISPLAY_PREVIEW_TEXT" => "Y",
                    "PROPERTY_CODE" => array("AMOUNT", "TYPE", "DATE"),
                    "FILTER_NAME" => "arrFilter",
                )
            );
            ?>
        </section>
    </div>
    <a href="/local/swagger">
        <button class="btn">Swagger</button>
    </a>
</main>

<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>