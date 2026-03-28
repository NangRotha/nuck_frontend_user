<?php
// confirm_payment.php — Handle payment confirmation with proof upload
require __DIR__ . '/config.php';

// Include language system
require_once __DIR__ . '/../includes/language.php';

// Get translation function
$t = function($key, $default = '') use ($lang) {
    return $lang->t($key, $default);
};

// Get current language for easy access
$currentLang = $lang->getCurrentLang();
$isKhmer = $currentLang === 'km';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php?lang=' . $currentLang);
    exit;
}

function redirect_with_error(int $student_id, string $msg, string $lang): void {
    header('Location: payment.php?id=' . $student_id . '&error=' . urlencode($msg) . '&lang=' . $lang);
    exit;
}

$student_id = isset($_POST['student_id']) ? (int)$_POST['student_id'] : 0;

if ($student_id <= 0) {
    redirect_with_error($student_id, $t('invalid_student_id'), $currentLang);
}

// Handle payment proof upload (required)
$proof_path = null;
$has_error = false;
$error_message = '';

if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
    redirect_with_error($student_id, $t('payment_proof_required'), $currentLang);
}

$file = $_FILES['payment_proof'];
$allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
$max_size = 5 * 1024 * 1024; // 5MB

// Validate file type
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mime_type = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mime_type, $allowed_types)) {
    redirect_with_error($student_id, $t('invalid_image_format'), $currentLang);
}

// Validate file size
if ($file['size'] > $max_size) {
    redirect_with_error($student_id, $t('image_too_large'), $currentLang);
}

// Create uploads directory if it doesn't exist
$upload_dir = __DIR__ . '/uploads/payment_proofs';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

// Generate unique filename
$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'payment_' . $student_id . '_' . date('Ymd_His') . '.' . $extension;
$destination = $upload_dir . '/' . $filename;

// Move uploaded file
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    redirect_with_error($student_id, $t('upload_error'), $currentLang);
}

$proof_path = 'uploads/payment_proofs/' . $filename;

try {
    $pdo = get_pdo();
    
    // Get student data first to verify existence
    $check_stmt = $pdo->prepare('SELECT id FROM students WHERE id = ?');
    $check_stmt->execute([$student_id]);
    $student_exists = $check_stmt->fetch();
    
    if (!$student_exists) {
        // Delete uploaded file if student not found
        if (file_exists($destination)) {
            unlink($destination);
        }
        redirect_with_error($student_id, $t('student_not_found'), $currentLang);
    }
    
    // Update payment status to 'pending' (waiting for admin verification)
    $stmt = $pdo->prepare('
        UPDATE students 
        SET payment_status = ?, 
            payment_proof_path = ?,
            payment_date = NOW(),
            updated_at = NOW()
        WHERE id = ?
    ');
    
    $stmt->execute(['pending', $proof_path, $student_id]);
    
    // Check if update was successful
    if ($stmt->rowCount() === 0) {
        throw new Exception('No rows updated');
    }
    
    // Redirect to success page
    header('Location: success.php?id=' . $student_id . '&lang=' . $currentLang);
    exit;
    
} catch (Throwable $e) {
    error_log('Payment confirmation error: ' . $e->getMessage());
    
    // Delete uploaded proof if database update fails
    if ($proof_path && file_exists(__DIR__ . '/' . $proof_path)) {
        unlink(__DIR__ . '/' . $proof_path);
    }
    
    // Redirect with error message
    $error_msg = $t('payment_confirmation_error');
    redirect_with_error($student_id, $error_msg, $currentLang);
}
?>