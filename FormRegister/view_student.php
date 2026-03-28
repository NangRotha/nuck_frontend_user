<?php
// view_student.php — View student details
require __DIR__ . '/config.php';

$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($student_id <= 0) {
    header('Location: dashboard.php');
    exit;
}

try {
    $pdo = get_pdo();
    $stmt = $pdo->prepare('SELECT * FROM students WHERE id = ? LIMIT 1');
    $stmt->execute([$student_id]);
    $student = $stmt->fetch();
    
    if (!$student) {
        header('Location: dashboard.php?error=' . urlencode('Student not found'));
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
    @media print {
      .no-print { display: none; }
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <div class="container mx-auto px-4 py-6 max-w-5xl">
    <!-- Header -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 no-print">
      <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-gray-900">Student Application Details</h1>
        <div class="flex gap-3">
          <button onclick="window.print()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Print
          </button>
          <a href="dashboard.php" class="px-4 py-2 border border-gray-300 rounded-lg hover:bg-gray-50">
            ← Back
          </a>
        </div>
      </div>
    </div>

    <!-- Student Information -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <!-- Header Section -->
      <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white p-6">
        <div class="flex items-center gap-6">
          <?php if ($student['photo_path']): ?>
            <img src="<?php echo htmlspecialchars($student['photo_path']); ?>" 
                 alt="Student Photo" 
                 class="w-32 h-40 object-cover rounded-lg border-4 border-white shadow-lg">
          <?php endif; ?>
          <div class="flex-1">
            <p class="text-sm opacity-90 mb-1">Application ID</p>
            <h2 class="text-3xl font-bold mb-3">#<?php echo $application_id; ?></h2>
            <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($student['name_khmer']); ?></h3>
            <p class="text-lg opacity-90"><?php echo htmlspecialchars($student['name_latin']); ?></p>
          </div>
          <div class="text-right">
            <?php
            $status_info = [
                'pending' => ['bg' => 'bg-orange-500', 'text' => 'Pending Payment'],
                'paid' => ['bg' => 'bg-green-500', 'text' => 'Paid'],
                'verified' => ['bg' => 'bg-blue-500', 'text' => 'Verified']
            ];
            $info = $status_info[$student['payment_status']] ?? ['bg' => 'bg-gray-500', 'text' => 'Unknown'];
            ?>
            <span class="inline-block px-4 py-2 <?php echo $info['bg']; ?> text-white rounded-full font-semibold">
              <?php echo $info['text']; ?>
            </span>
            <p class="mt-2 text-sm opacity-90">Applied: <?php echo date('d M Y', strtotime($student['created_at'])); ?></p>
          </div>
        </div>
      </div>

      <div class="p-8">
        <!-- Personal Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            Personal Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">Gender</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['gender']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Date of Birth</p>
              <p class="font-semibold text-gray-900"><?php echo date('d/m/Y', strtotime($student['dob'])); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Phone Number</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['phone']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Place of Birth</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['place_of_birth']); ?></p>
            </div>
            <?php if ($student['occupation']): ?>
            <div>
              <p class="text-sm text-gray-600">Occupation</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['occupation']); ?></p>
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Educational Background -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            Educational Background
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600">High School</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['high_school_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Graduation Year</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['graduated_year']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Student Type</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['student_type']); ?></p>
            </div>
          </div>
        </div>

        <!-- Family Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            Family Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">Father's Name</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['father_name_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Father's Phone</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['father_phone']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Mother's Name</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['mother_name_khmer']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Mother's Phone</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['mother_phone']); ?></p>
            </div>
          </div>
        </div>

        <!-- Program Selection -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            Program Selection
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">Degree Level</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['degree_level']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Faculty</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['faculty'] ?? 'N/A'); ?></p>
            </div>
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600">Program</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['program']); ?></p>
            </div>
          </div>
        </div>

        <!-- Payment Information -->
        <div class="mb-8">
          <h3 class="text-xl font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-600">
            Payment Information
          </h3>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
              <p class="text-sm text-gray-600">Payment Status</p>
              <p class="font-semibold text-gray-900"><?php echo ucfirst($student['payment_status']); ?></p>
            </div>
            <div>
              <p class="text-sm text-gray-600">Amount</p>
              <p class="font-semibold text-gray-900">$<?php echo number_format($student['payment_amount'], 2); ?> USD</p>
            </div>
            <?php if ($student['payment_reference']): ?>
            <div>
              <p class="text-sm text-gray-600">Transaction Reference</p>
              <p class="font-semibold text-gray-900"><?php echo htmlspecialchars($student['payment_reference']); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($student['payment_date']): ?>
            <div>
              <p class="text-sm text-gray-600">Payment Date</p>
              <p class="font-semibold text-gray-900"><?php echo date('d/m/Y H:i', strtotime($student['payment_date'])); ?></p>
            </div>
            <?php endif; ?>
            <?php if ($student['payment_proof_path']): ?>
            <div class="md:col-span-2">
              <p class="text-sm text-gray-600 mb-2">Payment Proof</p>
              <img src="<?php echo htmlspecialchars($student['payment_proof_path']); ?>" 
                   alt="Payment Proof" 
                   class="max-w-md rounded-lg border-2 border-gray-300 shadow-md">
            </div>
            <?php endif; ?>
          </div>
        </div>

        <!-- Actions -->
        <?php if ($student['payment_status'] === 'paid'): ?>
        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded no-print">
          <div class="flex items-center justify-between">
            <div>
              <h4 class="font-semibold text-yellow-800 mb-1">Payment Verification Required</h4>
              <p class="text-sm text-yellow-700">This payment needs to be verified by an administrator.</p>
            </div>
            <a href="verify_payment.php?id=<?php echo $student['id']; ?>" 
               class="px-6 py-3 bg-green-600 text-white rounded-lg font-semibold hover:bg-green-700 transition">
              Verify Payment
            </a>
          </div>
        </div>
        <?php endif; ?>
      </div>
    </div>
  </div>
</body>
</html>
