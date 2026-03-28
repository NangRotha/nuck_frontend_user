<?php
// test_submit.php - Debug version to test form submission
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Form Submission Test</h2>";
echo "<pre>";

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "✓ Form submitted via POST\n\n";
    
    echo "=== FORM DATA ===\n";
    print_r($_POST);
    
    echo "\n=== FILES DATA ===\n";
    print_r($_FILES);
    
    // Check required fields
    echo "\n=== VALIDATION CHECK ===\n";
    $required_fields = [
        'name_khmer', 'name_latin', 'gender', 'dob', 'phone', 'email',
        'place_of_birth', 'high_school_khmer', 'graduated_year', 'student_type',
        'father_name_khmer', 'father_phone', 'mother_name_khmer', 'mother_phone',
        'degree_level', 'faculty', 'program', 'declaration'
    ];
    
    $missing = [];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $missing[] = $field;
            echo "✗ Missing: $field\n";
        } else {
            echo "✓ Present: $field = " . $_POST[$field] . "\n";
        }
    }
    
    // Check photo
    echo "\n=== PHOTO CHECK ===\n";
    if (isset($_FILES['student_photo']) && $_FILES['student_photo']['error'] === UPLOAD_ERR_OK) {
        echo "✓ Photo uploaded successfully\n";
        echo "  Name: " . $_FILES['student_photo']['name'] . "\n";
        echo "  Size: " . $_FILES['student_photo']['size'] . " bytes\n";
        echo "  Type: " . $_FILES['student_photo']['type'] . "\n";
    } else {
        echo "✗ Photo upload issue\n";
        if (isset($_FILES['student_photo'])) {
            echo "  Error code: " . $_FILES['student_photo']['error'] . "\n";
        }
    }
    
    // Test database connection
    echo "\n=== DATABASE CONNECTION ===\n";
    try {
        require __DIR__ . '/config.php';
        $pdo = get_pdo();
        echo "✓ Database connected successfully\n";
        
        // Check if faculty column exists
        $stmt = $pdo->query("DESCRIBE students");
        $columns = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (in_array('faculty', $columns)) {
            echo "✓ Faculty column exists in database\n";
        } else {
            echo "✗ Faculty column MISSING in database!\n";
            echo "  Run migration_add_faculty.sql to fix this\n";
        }
        
        echo "\n=== TABLE STRUCTURE ===\n";
        $stmt = $pdo->query("DESCRIBE students");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo $row['Field'] . " - " . $row['Type'] . "\n";
        }
        
    } catch (Exception $e) {
        echo "✗ Database error: " . $e->getMessage() . "\n";
    }
    
    if (empty($missing)) {
        echo "\n✓✓✓ ALL VALIDATIONS PASSED ✓✓✓\n";
        echo "The form should work correctly!\n";
    } else {
        echo "\n✗✗✗ MISSING FIELDS ✗✗✗\n";
        echo "Missing: " . implode(', ', $missing) . "\n";
    }
    
} else {
    echo "No form submission detected. Please submit the form.\n";
}

echo "</pre>";

echo '<br><a href="index.php" style="padding: 10px 20px; background: #2563eb; color: white; text-decoration: none; border-radius: 5px;">← Back to Form</a>';
?>
