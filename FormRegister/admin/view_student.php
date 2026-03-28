<?php
// view_student.php — View student details
require __DIR__ . '/../config.php';

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
        header('Location: index.php?error=' . urlencode('Student not found'));
        exit;
    }
} catch (Throwable $e) {
    die('Database error: ' . $e->getMessage());
}

$application_id = str_pad($student['id'], 6, '0', STR_PAD_LEFT);
?>
<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Student Details - <?php echo htmlspecialchars($student['name_latin']); ?></title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&family=Kantumruy+Pro:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: 'Kantumruy Pro', 'Battambang', sans-serif; }
    .print-only { display: none; }
    @media print {
      .no-print { display: none; }
      .print-only { display: block; }
      .print-header {
        text-align: center;
        padding: 20px;
        border-bottom: 3px solid #2563eb;
        margin-bottom: 20px;
      }
      .print-header img {
        width: 80px;
        height: 80px;
        margin: 0 auto 15px;
      }
      .print-header h1 {
        font-size: 24px;
        font-weight: bold;
        color: #1e40af;
        margin-bottom: 5px;
      }
      .print-header h2 {
        font-size: 18px;
        color: #3b82f6;
        margin-bottom: 10px;
      }
      .print-header p {
        font-size: 20px;
        font-weight: bold;
        color: #1f2937;
      }
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <!-- Print-Only Header -->
  <div class="print-only print-header">
    <img src="../images/nuck_logo.png" alt="NUCK Logo">
    <h1>សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ</h1>
    <h2>National University of Cheasim Kamchaymear</h2>
    <p>ព័ត៌មានលម្អិតសិស្ស / Student Application Details</p>
  </div>

  <div class="container mx-auto px-4 py-6 max-w-5xl">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg shadow-lg p-6 mb-6 no-print">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <!-- University Logo -->
          <div class="bg-white rounded-full p-2 shadow-lg">
            <img src="../images/nuck_logo.png" alt="NUCK Logo" class="w-12 h-12">
          </div>
          <div>
            <h1 class="text-xl sm:text-2xl font-bold">ព័ត៌មានលម្អិតសិស្ស / Student Details</h1>
            <p class="text-xs sm:text-sm opacity-90">សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ</p>
          </div>
        </div>
        <div class="flex gap-3">
          <button onclick="window.print()" class="px-4 py-2 bg-white text-blue-600 rounded-lg hover:bg-blue-50 font-semibold">
            បោះពុម្ព / Print
          </button>
          <a href="index.php" class="px-4 py-2 border-2 border-white text-white rounded-lg hover:bg-white hover:text-blue-600 font-semibold transition">
            ← ត្រឡប់ / Back
          </a>
        </div>
      </div>
    </div>

    <!-- Alert Messages -->
    <?php if (!empty($_GET['error'])): ?>
      <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded no-print" role="alert">
        <p class="font-medium">⚠ <?php echo htmlspecialchars($_GET['error']); ?></p>
      </div>
    <?php endif; ?>
    <?php if (!empty($_GET['success'])): ?>
      <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded no-print" role="alert">
        <p class="font-medium">✓ <?php echo htmlspecialchars($_GET['success']); ?></p>
      </div>
    <?php endif; ?>

    <!-- Student Information -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
        <div class="flex items-center gap-6">
          <?php if ($student['photo_path']): ?>
            <img src="../<?php echo htmlspecialchars($student['photo_path']); ?>" 
                 alt="Student Photo" 
                 class="w-32 h-40 object-cover rounded-lg border-4 border-white shadow-lg">
          <?php endif; ?>
          <div class="flex-1">
            <p class="text-sm opacity-90 mb-1">លេខសម្គាល់ / Application ID</p>
            <h2 class="text-3xl font-bold mb-3">#<?php echo $application_id; ?></h2>
            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($student['name_khmer']); ?></h3>
            <p class="text-lg opacity-90"><?php echo htmlspecialchars($student['name_latin']); ?></p>
          </div>
          <div class="text-right">
            <?php
            // Determine actual payment status
            if ($student['payment_status'] === 'verified') {
                $status_bg = 'bg-blue-500';
                $status_text = 'បានផ្ទៀងផ្ទាត់ / Verified';
            } elseif ($student['payment_status'] === 'pending') {
                $status_bg = 'bg-orange-500';
                $status_text = 'រង់ចាំផ្ទៀងផ្ទាត់ / Pending Verification';
            } elseif ($student['payment_status'] === 'paid') {
                $status_bg = 'bg-green-500';
                $status_text = 'បានបង់ / Paid';
            } else {
                // not_paid status
                $status_bg = 'bg-red-500';
                $status_text = 'មិនបានបង់ / Not Paid';
            }
            ?>
            <span class="inline-block px-4 py-2 <?php echo $status_bg; ?> text-white rounded-full font-semibold">
              <?php echo $status_text; ?>
            </span>
            <p class="mt-2 text-sm opacity-90">ដាក់ពាក្យ / Applied: <?php echo date('d M Y', strtotime($student['created_at'])); ?></p>
          </div>
        </div>
      </div>

      <div class="p-8">
        <!-- Personal Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            ព័ត៌មានផ្ទាល់ខ្លួន / Personal Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">ភេទ / Gender</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['gender']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">ថ្ងៃខែឆ្នាំកំណើត / Date of Birth</p>
              <p class="font-semibold text-gray-900"><?php echo date('d/m/Y', strtotime($student['dob'])); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">លេខទូរស័ព្ទ / Phone Number</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['phone']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">អ៊ីមែល / Email Address</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['email']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">ទីកន្លែងកំណើត / Place of Birth</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['place_of_birth']); ?></p>
            </div>
            <?php if ($student['occupation']): ?>
            <div>
              <p class="text-sm text-gray-600">មុខរបរ / Occupation</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['occupation']); ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Educational Background -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            ប្រវត្តិការសិក្សា / Educational Background
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600">វិទ្យាល័យ / High School</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['high_school_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">ឆ្នាំបញ្ចប់ការសិក្សា / Graduation Year</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['graduated_year']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">ប្រភេទសិស្ស / Student Type</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['student_type']); ?></p>
            </div>
          </div>
        </div>

        <!-- Family Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            ព័ត៌មានគ្រួសារ / Family Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">ឈ្មោះឪពុក / Father's Name</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['father_name_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">លេខទូរស័ព្ទឪពុក / Father's Phone</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['father_phone']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">ឈ្មោះម្តាយ / Mother's Name</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['mother_name_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">លេខទូរស័ព្ទម្តាយ / Mother's Phone</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['mother_phone']); ?></p>
            </div>
          </div>
        </div>

        <!-- Program Selection -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            កម្មវិធីសិក្សា / Program Selection
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">កម្រិតសិក្សា / Degree Level</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['degree_level']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">មហាវិទ្យាល័យ / Faculty</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['faculty'] ?? 'N/A'); ?></p>
            </div>
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600">កម្មវិធី / Program</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['program']); ?></p>
            </div>
          </div>
        </div>

        <!-- Payment Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            ព័ត៌មានទូទាត់ / Payment Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">ស្ថានភាពទូទាត់ / Payment Status</p>
              <p class="font-semibold text-gray-900"><?php echo ucfirst($student['payment_status']); ?></p>
            </div>
            <?php if ($student['payment_reference']): ?>
            <div>
              <p class="text-sm text-gray-600">លេខយោង / Transaction Reference</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['payment_reference']); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($student['payment_date']): ?>
            <div>
              <p class="text-sm text-gray-600">ថ្ងៃទូទាត់ / Payment Date</p>
              <p class="font-semibold text-gray-900"><?php echo date('d/m/Y H:i', strtotime($student['payment_date'])); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($student['payment_proof_path']): ?>
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600 mb-2">ភស្តុតាងការទូទាត់ / Payment Proof</p>
              <img src="../<?php echo htmlspecialchars($student['payment_proof_path']); ?>" 
                   alt="Payment Proof" 
                   class="max-w-md rounded-lg border-2 border-gray-300 shadow-md">
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Actions -->
        <?php if ($student['payment_status'] === 'pending'): ?>
          <!-- Payment submitted - needs verification -->
          <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded no-print">
            <div class="flex items-center justify-between">
              <div>
                <h4 class="font-semibold text-yellow-800 mb-1">ត្រូវការផ្ទៀងផ្ទាត់ការទូទាត់ / Payment Verification Required</h4>
                <p class="text-sm text-yellow-700">ការទូទាត់នេះត្រូវការផ្ទៀងផ្ទាត់ដោយអ្នកគ្រប់គ្រង / This payment needs to be verified by an administrator.</p>
              </div>
              <a href="verify_payment.php?id=<?php echo $student['id']; ?>" 
                 class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
                ផ្ទៀងផ្ទាត់ / Verify Payment
              </a>
            </div>
          </div>
        <?php elseif ($student['payment_status'] === 'not_paid'): ?>
          <!-- Payment not submitted yet -->
          <div class="bg-red-50 border-l-4 border-red-500 p-6 rounded no-print">
            <div class="flex items-center">
              <svg class="w-6 h-6 text-red-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-red-800 mb-1">⚠️ មិនបានបង់ប្រាក់ / Payment Not Submitted</h4>
                <p class="text-sm text-red-700">សិស្សមិនទាន់បានបញ្ជាក់ការទូទាត់នៅឡើយទេ។ សូមទាក់ទងសិស្សដើម្បីបញ្ជាក់ការទូទាត់។</p>
                <p class="text-sm text-red-700">Student has not confirmed payment yet. Please contact the student to complete payment.</p>
              </div>
            </div>
          </div>
        <?php elseif ($student['payment_status'] === 'verified'): ?>
        <div class="bg-blue-50 border-l-4 border-blue-500 p-6 rounded no-print">
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <svg class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              <div>
                <h4 class="font-semibold text-blue-800 mb-1">✓ បានផ្ទៀងផ្ទាត់ / Payment Verified</h4>
                <p class="text-sm text-blue-700">ការទូទាត់នេះត្រូវបានផ្ទៀងផ្ទាត់។ អ្នកអាចលុបចោលការផ្ទៀងផ្ទាត់បាន / This payment has been verified. You can unverify if needed.</p>
              </div>
            </div>
            <a href="unverify_payment.php?id=<?php echo $student['id']; ?>" 
               onclick="return confirm('តើអ្នកប្រាកដថាចង់លុបចោលការផ្ទៀងផ្ទាត់នេះទេ? / Are you sure you want to unverify this payment?')"
               class="px-6 py-3 bg-orange-600 text-white rounded-lg font-semibold hover:bg-orange-700 transition">
              លុបចោលការផ្ទៀងផ្ទាត់ / Unverify Payment
            </a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
