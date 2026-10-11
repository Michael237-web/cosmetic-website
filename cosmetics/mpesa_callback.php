<?php
require_once __DIR__ . '/db.php';

$raw  = file_get_contents('php://input');
file_put_contents(__DIR__ . '/mpesa_log.txt', date('c') . " " . $raw . PHP_EOL, FILE_APPEND);

$data = json_decode($raw, true);

$checkoutId = $data['Body']['stkCallback']['CheckoutRequestID'] ?? null;
$resultCode = $data['Body']['stkCallback']['ResultCode']       ?? null;
$items      = $data['Body']['stkCallback']['CallbackMetadata']['Item'] ?? [];

if ($checkoutId) {
    $receipt = null;
    foreach ($items as $item) {
        if (($item['Name'] ?? '') === 'MpesaReceiptNumber') {
            $receipt = $item['Value'] ?? null;
        }
    }

    $status = ((int)$resultCode === 0) ? 'paid' : 'failed';

    $stmt = db()->prepare("
        UPDATE cosmetics_orders
        SET status = ?, mpesa_receipt = ?, paid_at = " .
        ($status === 'paid' ? 'NOW()' : 'NULL') . "
        WHERE mpesa_checkout_id = ?
    ");
    $stmt->execute([$status, $receipt, $checkoutId]);
}

header('Content-Type: application/json');
echo json_encode(['ResultCode' => 0, 'ResultDesc' => 'Success']);