<?php
session_start();
require_once __DIR__ . '/../includes/currency.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

$currency = $_POST['currency'] ?? '';

if (CurrencyConverter::setSelectedCurrency($currency)) {
    echo json_encode([
        'success' => true,
        'currency' => $currency,
        'message' => 'Currency updated successfully'
    ]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid currency'
    ]);
}
?>
