<?php
// submit.php — handles form submission for University Admission
error_reporting(E_ALL);
ini_set('display_errors', 1);

require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

function redirect_with_error(string $msg): void {
    // Log error for debugging
    error_log("Form submission error: " . $msg);
    header('Location: index.php?error=' . urlencode($msg));
    exit;
}

// Collect form data
$name_khmer = trim($_POST['name_khmer'] ?? '');
$name_latin = trim($_POST['name_latin'] ?? '');
$gender = trim($_POST['gender'] ?? '');
$dob = trim($_POST['dob'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$email = trim($_POST['email'] ?? '');
$place_of_birth = trim($_POST['place_of_birth'] ?? '');
$occupation = trim($_POST['occupation'] ?? '');
$high_school_khmer = trim($_POST['high_school_khmer'] ?? '');
$graduated_year = trim($_POST['graduated_year'] ?? '');
$student_type = trim($_POST['student_type'] ?? '');
$father_name_khmer = trim($_POST['father_name_khmer'] ?? '');
$father_phone = trim($_POST['father_phone'] ?? '');
$mother_name_khmer = trim($_POST['mother_name_khmer'] ?? '');
$mother_phone = trim($_POST['mother_phone'] ?? '');
$degree_level = trim($_POST['degree_level'] ?? '');
$faculty = trim($_POST['faculty'] ?? '');
$program = trim($_POST['program'] ?? '');
$declaration = isset($_POST['declaration']) ? 1 : 0;

// Validate required fields with detailed logging
$missing_fields = [];
if ($name_khmer === '') $missing_fields[] = 'name_khmer';
if ($name_latin === '') $missing_fields[] = 'name_latin';
if ($gender === '') $missing_fields[] = 'gender';
if ($dob === '') $missing_fields[] = 'dob';
if ($phone === '') $missing_fields[] = 'phone';
if ($email === '') $missing_fields[] = 'email';
if ($place_of_birth === '') $missing_fields[] = 'place_of_birth';
if ($high_school_khmer === '') $missing_fields[] = 'high_school_khmer';
if ($graduated_year === '') $missing_fields[] = 'graduated_year';
if ($student_type === '') $missing_fields[] = 'student_type';
if ($father_name_khmer === '') $missing_fields[] = 'father_name_khmer';
if ($father_phone === '') $missing_fields[] = 'father_phone';
if ($mother_name_khmer === '') $missing_fields[] = 'mother_name_khmer';
if ($mother_phone === '') $missing_fields[] = 'mother_phone';
if ($degree_level === '') $missing_fields[] = 'degree_level';
if ($faculty === '') $missing_fields[] = 'faculty';
if ($program === '') $missing_fields[] = 'program';

if (!empty($missing_fields)) {
    $error_msg = 'សូមបំពេញព័ត៌មានទាំងអស់ដែលមានសញ្ញា * / Please fill in all required fields. Missing *';
    error_log("Missing fields: " . implode(', ', $missing_fields));
    redirect_with_error($error_msg);
}

// Validate declaration checkbox
if (!$declaration) {
    redirect_with_error('សូមធីកសេចក្តីប្រកាស / Please check the declaration checkbox.');
}

// Validate date format
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
    redirect_with_error('ទម្រង់ថ្ងៃខែឆ្នាំមិនត្រឹមត្រូវ / Invalid date of birth format.');
}

// Handle photo upload
$photo_path = null;
if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
    $file = $_FILES['student_photo'];
    $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
    $max_size = 2 * 1024 * 1024; // 2MB
    
    // Validate file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    $mime_type = finfo_file($finfo, $file['tmp_name']);
    finfo_close($finfo);
    
    if (!in_array($mime_type, $allowed_types)) {
        redirect_with_error('ទម្រង់រូបថតមិនត្រឹមត្រូវ។ សូមប្រើ JPG ឬ PNG / Invalid photo format. Please use JPG or PNG.');
    }
    
    // Validate file size
    if ($file['size'] > $max_size) {
        redirect_with_error('ទំហំរូបថតធំពេក។ អតិបរមា 2MB / Photo size too large. Maximum 2MB.');
    }
    
    // Create uploads directory if it doesn't exist
    $upload_dir = __DIR__ . '/uploads/photos';
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }
    
    // Generate unique filename
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $filename = uniqid('student_', true) . '.' . $extension;
    $destination = $upload_dir . '/' . $filename;
    
    // Move uploaded file with detailed error handling
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        error_log("Photo upload failed | Destination: $destination | File size: " . $file['size']);
        redirect_with_error('មានបញ្ហាក្នុងការផ្ទុករូបថត / Error uploading photo.');
    }
    
    $photo_path = 'uploads/photos/' . $filename;
} else if (!isset($_FILES['student_photo']) || $_FILES['student_photo']['error'] !== UPLOAD_ERR_NO_FILE) {
    redirect_with_error('សូមជ្រើសរើសរូបថតសិស្ស / Please select a student photo.');
}

// Calculate payment amount based on degree level
$payment_amounts = [
    'Associate' => 450.00,
    'Bachelor' => 500.00,
    'Master' => 500.00,
    'Doctoral' => 500.00
];
$payment_amount = $payment_amounts[$degree_level] ?? 100.00;

try {
    $pdo = get_pdo();

    // Insert student record with 'not_paid' status initially
    $stmt = $pdo->prepare('
        INSERT INTO students (
            photo_path, name_khmer, name_latin, gender, dob, phone, email,
            place_of_birth, occupation, high_school_khmer, graduated_year, student_type,
            father_name_khmer, father_phone, mother_name_khmer, mother_phone,
            degree_level, faculty, program, payment_amount, payment_status, declaration
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ');
    
    $stmt->execute([
        $photo_path,
        $name_khmer,
        $name_latin,
        $gender,
        $dob,
        $phone,
        $email,
        $place_of_birth,
        $occupation !== '' ? $occupation : null,
        $high_school_khmer,
        $graduated_year,
        $student_type,
        $father_name_khmer,
        $father_phone,
        $mother_name_khmer,
        $mother_phone,
        $degree_level,
        $faculty,
        $program,
        $payment_amount,
        'not_paid',  // Initial status: not paid yet
        $declaration
    ]);

    $student_id = $pdo->lastInsertId();
    // Redirect to payment page instead of success page
    header('Location: payment.php?id=' . $student_id);
    exit;
} catch (Throwable $e) {
    // Log the error in development
    error_log('Database error: ' . $e->getMessage());
    error_log('Stack trace: ' . $e->getTraceAsString());
    
    // Delete uploaded photo if database insert fails
    if ($photo_path && file_exists(__DIR__ . '/' . $photo_path)) {
        unlink(__DIR__ . '/' . $photo_path);
    }
    
    // Show detailed error in development
    $error_detail = $e->getMessage();
    if (strpos($error_detail, 'faculty') !== false) {
        redirect_with_error('Database error: Faculty column missing. Please run migration_add_faculty.sql');
    } else {
        redirect_with_error('មានបញ្ហាក្នុងការរក្សាទុកទិន្នន័យ / Database error: ' . $error_detail);
    }
}
