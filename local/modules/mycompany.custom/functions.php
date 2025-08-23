<?php if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/**
 * Здесь можно объявлять собственные функции
 */

///**
// * Возвращает баланс пользователя, если он авторизирован. В противном случае - null.
// * Если баланс не задан, то устанавливает из значения по-умолчанию, определённому в constants.php
// * @return int|null
// */
//function getUserBalance(): ?int
//{
//    global $USER;
//
//    if(!$USER->IsAuthorized()) {
//        return null;
//    }
//
//    $userId = $USER->GetId();
//
//    $fields = CUser::GetByID($userId)->Fetch();
//
//    if(is_null($balance = $fields['UF_BALANCE'])) {
//        $balance = USER_DEFAULT_BALANCE;
//
//        $fieldsToSet = ['UF_BALANCE' => $balance];
//
//        $userObj = new CUser();
//        $userObj->Update($userId, $fieldsToSet);
//    }
//
//    return $balance;
//}