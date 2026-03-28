<?php
// verify_payment.php — Verify student payment
require __DIR__ . '/config.php';

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

try {
    $pdo = get_pdo();
    
    // Update payment status to verified
    $stmt = $pdo->prepare('
        UPDATE students 
        SET payment_status = "verified"
        WHERE id = ? AND payment_status = "paid"
    ');
    $stmt->execute([$student_id]);
    
    if ($stmt->rowCount() > 0) {
        header('Location: view_student.php?id=' . $student_id . '&success=' . urlencode('Payment verified successfully'));
    } else {
        header('Location: view_student.php?id=' . $student_id . '&error=' . urlencode('Payment already verified or not found'));
    }
    exit;
    
} catch (Throwable $e) {
    error_log('Payment verification error: ' . $e->getMessage());
    header('Location: view_student.php?id=' . $student_id . '&error=' . urlencode('Error verifying payment'));
    exit;
}
