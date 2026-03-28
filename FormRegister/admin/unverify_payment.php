<?php
// unverify_payment.php — Unverify student payment
require __DIR__ . '/../config.php';

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = get_pdo();
    
    // Update payment status back to paid
    $stmt = $pdo->prepare('
        UPDATE students 
        SET payment_status = "paid"
        WHERE id = ? AND payment_status = "verified"
    ');
    $stmt->execute([$student_id]);
    
    if ($stmt->rowCount() > 0) {
        header('Location: view_student.php?id=' . $student_id . '&success=' . urlencode('Payment unverified successfully'));
    } else {
        header('Location: view_student.php?id=' . $student_id . '&error=' . urlencode('Payment is not verified or not found'));
    }
    exit;
    
} catch (Throwable $e) {
    error_log('Payment unverification error: ' . $e->getMessage());
    header('Location: view_student.php?id=' . $student_id . '&error=' . urlencode('Error unverifying payment'));
    exit;
}
