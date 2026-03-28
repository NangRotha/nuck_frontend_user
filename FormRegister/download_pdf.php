<?php
// download_pdf.php — Generate PDF receipt
require __DIR__ . '/config.php';

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id <= 0) {
    header('Location: index.php');
    exit;
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ? LIMIT 1');
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        header('Location: index.php?error=' . urlencode('Application not found'));
        exit;
    }
} catch (Throwable $e) {
    die('Database error: ' . $e->getMessage());
}

$application_id = str_pad($student['id'], 6, '0', STR_PAD_LEFT);

// This page is designed to be printed to PDF using browser's print function
// The auto-print script at the bottom will trigger the print dialog
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Application Receipt - <?php echo $application_id; ?></title>
    <style>
        @page { margin: 20mm; }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            color: #2563eb;
            margin: 10px 0;
            font-size: 24pt;
        }
        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }
        .section-title {
            background: #2563eb;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14pt;
            margin-bottom: 15px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            margin-bottom: 10px;
        }
        .info-label {
            font-weight: bold;
            color: #666;
            font-size: 10pt;
        }
        .info-value {
            color: #000;
            font-size: 12pt;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-weight: bold;
            margin-top: 10px;
        }
        .status-paid {
            background: #10b981;
            color: white;
        }
        .status-verified {
            background: #3b82f6;
            color: white;
        }
        .status-pending {
            background: #f59e0b;
            color: white;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10pt;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
        .photo {
            float: right;
            width: 120px;
            height: 150px;
            border: 2px solid #ddd;
            margin-left: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>សាកលវិទ្យាល័យជាតិ</h1>
        <h2>National University of Cambodia</h2>
        <h3>Application Receipt</h3>
        <p><strong>Application ID: #<?php echo $application_id; ?></strong></p>
        <p>Date: <?php echo date('d F Y', strtotime($student['created_at'])); ?></p>
    </div>

    <?php if ($student['photo_path'] && file_exists(__DIR__ . '/' . $student['photo_path'])): ?>
        <img src="<?php echo $student['photo_path']; ?>" class="photo" alt="Student Photo">
    <?php endif; ?>

    <div class="section">
        <div class="section-title">Personal Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Name (Khmer):</div>
                <div class="info-value"><?php echo htmlspecialchars($student['name_khmer']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Name (Latin):</div>
                <div class="info-value"><?php echo htmlspecialchars($student['name_latin']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Gender:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['gender']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Date of Birth:</div>
                <div class="info-value"><?php echo date('d/m/Y', strtotime($student['dob'])); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Phone:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['phone']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Place of Birth:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['place_of_birth']); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Educational Background</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">High School:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['high_school_khmer']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Graduation Year:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['graduated_year']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Student Type:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['student_type']); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Family Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Father's Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['father_name_khmer']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Father's Phone:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['father_phone']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Mother's Name:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['mother_name_khmer']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Mother's Phone:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['mother_phone']); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Program Selection</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Degree Level:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['degree_level']); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Faculty:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['faculty'] ?? 'N/A'); ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Program:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['program']); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Payment Information</div>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Payment Status:</div>
                <div class="info-value">
                    <?php
                    $status_class = [
                        'pending' => 'status-pending',
                        'paid' => 'status-paid',
                        'verified' => 'status-verified'
                    ];
                    $class = $status_class[$student['payment_status']] ?? 'status-pending';
                    ?>
                    <span class="status-badge <?php echo $class; ?>">
                        <?php echo strtoupper($student['payment_status']); ?>
                    </span>
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Amount:</div>
                <div class="info-value">$<?php echo number_format($student['payment_amount'], 2); ?> USD</div>
            </div>
            <?php if ($student['payment_reference']): ?>
            <div class="info-item">
                <div class="info-label">Transaction Reference:</div>
                <div class="info-value"><?php echo htmlspecialchars($student['payment_reference']); ?></div>
            </div>
            <?php endif; ?>
            <?php if ($student['payment_date']): ?>
            <div class="info-item">
                <div class="info-label">Payment Date:</div>
                <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($student['payment_date'])); ?></div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="footer">
        <p><strong>This is a computer-generated document. No signature is required.</strong></p>
        <p>For inquiries, please contact: +855 12 345 678 | admissions@university.edu.kh</p>
        <p>© 2025 National University of Cambodia. All rights reserved.</p>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        };
    </script>
</body>
</html>
