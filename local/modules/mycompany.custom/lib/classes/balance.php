<?php

namespace MyCompany\Custom\Classes;

use CIBlockElement;
use CIBlockResult;

if (!\CModule::IncludeModule("iblock")) {
    die("Модуль инфоблоков не установлен");
}

class Balance
{
    private static $operations = [
        'credit' => 4,
        'debit' => 3
    ];

    static function getUserBalanceFromTransactions(?int $userId = null)
    {
        global $USER;

        if(!$USER->IsAuthorized() && !isset($userId)) {
            return null;
        }

        $userId = $userId ?: $USER->GetId();
        $balance = 0;

        $credits = self::getTransactions($userId, 'credit');
        $debits = self::getTransactions($userId, 'debit');

        while ($creditResult = $credits->Fetch()) {
            $balance += $creditResult["PROPERTY_AMOUNT_VALUE"];
        }

        while ($debitResult = $debits->Fetch()) {
            $balance -= $debitResult["PROPERTY_AMOUNT_VALUE"];
        }

        return $balance;
    }

    static function getTransactions(int $userId, ?string $operationType = null, array $params = []): CIBlockResult|null
    {
        $typeId = self::$operations[$operationType];
        if (isset($operationType) && !$typeId) {
            return null;
        }

        $selectAs = $params["SELECT_AS"] ?: [];
        $page = $params["PAGE"] ?: null;
        $limit = $params["LIMIT"] ?: null;

        $arNavStartParams = [];

        if (isset($limit)) {
            $arNavStartParams['nPageSize'] = $limit;
        }

        if (isset($page)) {
            $arNavStartParams['nPage'] = $page;
        }

        return CIBlockElement::GetList(
            array("DATE_CREATE" => "DESC"),
            array(
                "IBLOCK_ID" => TRANSACTIONS_IBLOCK_ID,
                "PROPERTY_USER_ID" => $userId,
                "PROPERTY_TYPE" => $typeId,
            ),
            false,
            $arNavStartParams,
            array("PROPERTY_AMOUNT", ...$selectAs)
        );
    }

    static function createTransaction(int $userId, string $operationType, int $amount): ?bool
    {
        if (!$typeId = self::$operations[$operationType]) {
            return null;
        }

        $isCredit = $operationType === 'credit';
        $cib = new CIBlockElement();

        // Если списание, то не даём списывать больше, чем есть на счёте
        if (!$isCredit) {
            $balance = self::getUserBalanceFromTransactions();
            if ($balance < $amount) {
                $amount = $balance;
            }
        }

        // Если операции нету, то и не выполняем
        if ($amount <= 0 ) {
            return null;
        }

        $name = sprintf("%s на сумму %s баллов пользователя #%s",
            $isCredit ? 'Пополнение' : 'Списание',
            $amount,
            $userId
        );

        $params = array(
            "IBLOCK_ID" => TRANSACTIONS_IBLOCK_ID,
            "NAME" => $name,
            "PROPERTY_VALUES" => array(
                "USER_ID" => $userId,
                "TYPE" => $typeId,
                "AMOUNT" => $amount,
            ),
            "ACTIVE" => "Y",
            "DATE_ACTIVE_FROM" => ConvertTimeStamp(time(), "FULL")
        );

        if ($cib->Add($params)) {
            return true;
        }

        return false;
    }
}