<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<?php
global $APPLICATION;
$APPLICATION->SetAdditionalCSS("/local/templates/.default/components/bitrix/news.list/transactions/styles.css");
?>


<?php if (count($arResult["ITEMS"]) > 0): ?>
    <div class="transactions-list">
        <?php foreach ($arResult["ITEMS"] as $arItem): ?>
            <div class="transaction">
                <p><?= FormatDate("d.m.Y H:i", MakeTimeStamp($arItem["ACTIVE_FROM"])); ?></p>
                <p>|</p>
                <p class="transaction-type"><?= $arItem['PROPERTIES']['TYPE']['VALUE'] === 'debit' ? 'Зачисление' : 'Списание' ?></p>
                <p>|</p>
                <p><?= $arItem['PROPERTIES']['AMOUNT']['VALUE']; ?> баллов</p>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Пагинация -->
    <div class="pagination">
        <?= $arResult["NAV_STRING"]; ?>
    </div>

<?php else: ?>
    <p>История операций пуста.</p>
<?php endif; ?>