<?php
define('NO_KEEP_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

use MyCompany\Custom\Classes\Balance;

// Помечаем, что ответ будет в формате JSON
header('Content-Type: application/json; charset=utf-8');

$userId = (int)($_GET['userId'] ?? 0);
$page = (int)($_GET['page'] ?? 1);
$limit = (int)($_GET['limit'] ?? 10);

$page = max($page, 1); // page не может быть нулём
$limit = min(max($limit, 1), 100); // Не отдаём больше 100 за раз и меньше 1

if ($userId <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'userId required']);
    exit;
}

$totalCount = Balance::getTransactions($userId, null)->SelectedRowsCount();
$transactionsCI = Balance::getTransactions($userId, null, [
    // Поля, которые должны быть возвращены в "пагинаторе"
    'SELECT_AS' => array('ID', 'PROPERTY_TYPE', 'DATE_CREATE'),
    'PAGE' => $page,
    'LIMIT' => $limit,
]);
$transactions = [];

while ($transaction = $transactionsCI->Fetch()) {
    $transactions[] = [
        'id' => $transaction['ID'],
        'amount' => $transaction['PROPERTY_AMOUNT_VALUE'],
        'type' => $transaction['PROPERTY_TYPE_VALUE'],
        'date' => $transaction['DATE_CREATE']
    ];
}

echo json_encode([
    'userId' => $userId,
    'history' => $transactions,
    'pagination' => [
        'page' => $page,
        'limit' => $limit,
        'totalCount' => $totalCount,
        'totalPages' => ceil($totalCount / $limit),
    ]
]);
