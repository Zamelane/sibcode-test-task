<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use MyCompany\Custom\Classes\Balance;

// Помечаем, что ответ будет в формате JSON
header('Content-Type: application/json; charset=utf-8');

$userId = (int)($_GET['userId'] ?? 0);
if ($userId <= 0 || !$user = CUser::GetByID($userId)->Fetch()) {
    http_response_code(400);
    echo json_encode(['error' => 'user $userId not found']);
    exit;
}

echo json_encode([
    'userId' => $userId,
    'balance' => Balance::getUserBalanceFromTransactions($userId)
]);