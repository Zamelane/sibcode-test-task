<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Личный кабинет");

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

<h1>Личный кабинет</h1>
<p>Здравствуйте, <?=$USER->GetFirstName()?>!</p>
<p>Ваш id: <?=$USER->GetID()?></p>

<div>
    <p>
        <?=$balance?>
        баллов
    </p>
</div>

<section>
    <h2>Операция с баллами</h2>
    <form method="POST">
        <input type="number" id="amount" name="amount" min="1" placeholder="Баллов к начислению" required>
        <button type="submit" name="submit" value="credit">Начислить</button>
    </form>
    <form method="POST">
        <input type="number" id="amount" name="amount" min="1" placeholder="Баллов к списанию" required>
        <button type="submit" name="submit" value="debit">Списать</button>
    </form>
</section>

<section>
    <h2>История операций</h2>
    <?php
    $APPLICATION->IncludeComponent(
        "bitrix:news.list",
        "transactions",
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
        )
    );
    ?>
</section>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>