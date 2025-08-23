<?php

namespace MyCompany\Custom\EventHandlers;

use MyCompany\Custom\Classes\Balance;

class User {
    static function onUserRegister($fields)
    {
        $userId = $fields['ID'];

        Balance::createTransaction($userId, 'credit', USER_DEFAULT_BALANCE);
    }
}