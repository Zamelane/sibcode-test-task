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

    static function getUserBalanceFromTransactions()
    {
        global $USER;

        if(!$USER->IsAuthorized()) {
            return null;
        }

        $userId = $USER->GetId();
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

    static function getTransactions(int $userId, string $operationType): CIBlockResult|null
    {
        if (!$typeId = self::$operations[$operationType]) {
            return null;
        }

        return CIBlockElement::GetList(
            array(),
            array(
                "IBLOCK_ID" => TRANSACTIONS_IBLOCK_ID,
                "PROPERTY_USER_ID" => $userId,
                "PROPERTY_TYPE" => $typeId,
            ),
            false,
            false,
            array("PROPERTY_AMOUNT")
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