<?php
// dashboard.php — Admin Dashboard
require __DIR__ . '/../config.php';

// Get filter parameters
$filter_status = $_GET['status'] ?? 'all';
$search = trim($_GET['search'] ?? '');

try {
    $pdo = get_pdo();
    
    // Build query based on filters
    $where_clauses = [];
    $params = [];
    
    if ($filter_status !== 'all') {
        $where_clauses[] = 'payment_status = ?';
        $params[] = $filter_status;
    }
    
    if ($search !== '') {
        $where_clauses[] = '(name_khmer LIKE ? OR name_latin LIKE ? OR phone LIKE ? OR email LIKE ? OR program LIKE ? OR degree_level LIKE ? OR id = ?)';
        $search_param = '%' . $search . '%';
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search_param;
        $params[] = $search;
    }
    
    $where_sql = count($where_clauses) > 0 ? 'WHERE ' . implode(' AND ', $where_clauses) : '';
    
    // Get total counts for statistics
    $stats_query = $pdo->query('
        SELECT 
            COUNT(*) as total,
            SUM(CASE WHEN payment_status = "pending" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN payment_status = "paid" THEN 1 ELSE 0 END) as paid,
            SUM(CASE WHEN payment_status = "verified" THEN 1 ELSE 0 END) as verified,
            SUM(CASE WHEN payment_status = "not_paid" THEN 1 ELSE 0 END) as not_paid,
            SUM(payment_amount) as total_amount
        FROM students
    ');
    $stats = $stats_query->fetch();
    
    // Get students list
    $stmt = $pdo->prepare("
        SELECT id, photo_path, name_khmer, name_latin, phone, email, program, degree_level, 
               payment_status, payment_amount, payment_reference, created_at
        FROM students 
        $where_sql
        ORDER BY created_at DESC
    ");
    $stmt->execute($params);
    $students = $stmt->fetchAll();
    
} catch (Throwable $e) {
    die('Database error: ' . $e->getMessage());
}
?>
<!doctype html>
<html lang="km">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - University Admission System</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Battambang:wght@100;300;400;700;900&family=Kantumruy+Pro:wght@100;200;300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Kantumruy Pro', 'Battambang', sans-serif;
    }
    .stat-card {
      transition: transform 0.2s, box-shadow 0.2s;
    }
    .stat-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }
    .table-row:hover {
      background-color: #eff6ff;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .fade-in {
      animation: fadeIn 0.5s ease-out;
    }
  </style>
</head>
<body class="bg-gray-100 min-h-screen">
  <!-- Header -->
  <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
      <div class="flex items-center justify-between">
        <div class="flex items-center gap-4">
          <!-- University Logo -->
          <div class="bg-white rounded-full p-2 shadow-lg">
            <img src="../images/nuck_logo.png" alt="NUCK Logo" class="w-12 h-12">
          </div>
          <div>
            <h1 class="text-xl sm:text-2xl font-bold">Admin Dashboard</h1>
            <p class="text-xs sm:text-sm opacity-90">សាកលវិទ្យាល័យជាតិជាស៊ីមកំចាយមារ / NUCK Admission System</p>
          </div>
        </div>
        <a href="../index.php" class="px-4 py-2 bg-white text-blue-600 rounded-lg font-semibold hover:bg-blue-50 transition">
          ← Back to Form
        </a>
      </div>
    </div>
  </div>

  <div class="container mx-auto px-4 py-6">
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-6 gap-4 mb-6">
      <div class="bg-white rounded-lg shadow-lg p-6 stat-card fade-in">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">ពាក្យសុំសរុប / Total Applications</p>
            <p class="text-3xl font-bold text-gray-900"><?php echo $stats['total']; ?></p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg p-6 stat-card fade-in" style="animation-delay: 0.1s">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">រង់ចាំទូទាត់ / Pending Payment</p>
            <p class="text-3xl font-bold text-orange-600"><?php echo $stats['pending']; ?></p>
          </div>
          <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-orange-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg p-6 stat-card fade-in" style="animation-delay: 0.2s">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">បានបង់ / Paid</p>
            <p class="text-3xl font-bold text-green-600"><?php echo $stats['paid']; ?></p>
          </div>
          <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-green-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg p-6 stat-card fade-in" style="animation-delay: 0.3s">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">បានផ្ទៀងផ្ទាត់ / Verified</p>
            <p class="text-3xl font-bold text-blue-600"><?php echo $stats['verified']; ?></p>
          </div>
          <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>

      <div class="bg-white rounded-lg shadow-lg p-6 stat-card fade-in" style="animation-delay: 0.4s">
        <div class="flex items-center justify-between">
          <div>
            <p class="text-gray-600 text-sm">មិនបានបង់ / Not Paid</p>
            <p class="text-3xl font-bold text-red-600"><?php echo $stats['not_paid']; ?></p>
          </div>
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center">
            <svg class="w-6 h-6 text-red-600" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
          </div>
        </div>
      </div>

    </div>

    <!-- Filters and Search -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 fade-in" style="animation-delay: 0.5s">
      <form method="get" class="flex flex-col md:flex-row gap-4">
        <div class="flex-1">
          <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
                 placeholder="ស្វែងរកតាមឈ្មោះ លេខទូរស័ព្ទ អ៊ីមែល កម្មវិធី កម្រិត ឬលេខសម្គាល់ / Search by name, phone, email, program, level, or ID..." 
                 class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
        </div>
        <div>
          <select name="status" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            <option value="all" <?php echo ($filter_status === 'all') ? 'selected' : ''; ?>>ស្ថានភាពទាំងអស់ / All Status</option>
            <option value="pending" <?php echo ($filter_status === 'pending') ? 'selected' : ''; ?>>រង់ចាំ / Pending</option>
            <option value="paid" <?php echo ($filter_status === 'paid') ? 'selected' : ''; ?>>បានបង់ / Paid</option>
            <option value="verified" <?php echo ($filter_status === 'verified') ? 'selected' : ''; ?>>បានផ្ទៀងផ្ទាត់ / Verified</option>
            <option value="not_paid" <?php echo ($filter_status === 'not_paid') ? 'selected' : ''; ?>>មិនបានបង់ / Not Paid</option>
          </select>
        </div>
        <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
          ស្វែងរក / Search
        </button>
      </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow-lg overflow-hidden fade-in" style="animation-delay: 0.6s">
      <div class="overflow-x-auto">
        <table class="w-full">
          <thead class="bg-gray-50 border-b">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">លេខសម្គាល់ / ID</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">រូបភាព / Photo</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ឈ្មោះ / Name</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ទូរស័ព្ទ / Phone</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">អ៊ីមែល / Email</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">កម្មវិធី / Program</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">កម្រិត / Level</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ស្ថានភាព / Status</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">កាលបរិច្ឆេទ / Date</th>
              <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">សកម្មភាព / Actions</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <?php if (count($students) > 0): ?>
              <?php foreach ($students as $student): ?>
                <tr class="table-row cursor-pointer transition" onclick="window.location='view_student.php?id=<?php echo $student['id']; ?>'">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    #<?php echo str_pad($student['id'], 6, '0', STR_PAD_LEFT); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php if ($student['photo_path']): ?>
                      <img src="../<?php echo htmlspecialchars($student['photo_path']); ?>" 
                           alt="Photo" class="w-10 h-12 object-cover rounded">
                    <?php else: ?>
                      <div class="w-10 h-12 bg-gray-200 rounded flex items-center justify-center">
                        <svg class="w-6 h-6 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                        </svg>
                      </div>
                    <?php endif; ?>
                  </td>
                  <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($student['name_khmer']); ?></div>
                    <div class="text-sm text-gray-500"><?php echo htmlspecialchars($student['name_latin']); ?></div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo htmlspecialchars($student['phone']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo htmlspecialchars($student['email']); ?>
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-900">
                    <?php echo htmlspecialchars($student['program']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <?php echo htmlspecialchars($student['degree_level']); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <?php
                    $status_colors = [
                        'pending' => 'bg-orange-100 text-orange-800',
                        'paid' => 'bg-green-100 text-green-800',
                        'verified' => 'bg-blue-100 text-blue-800',
                        'not_paid' => 'bg-red-100 text-red-800'
                    ];
                    $color = $status_colors[$student['payment_status']] ?? 'bg-gray-100 text-gray-800';
                    ?>
                    <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo $color; ?>">
                      <?php echo ucfirst($student['payment_status']); ?>
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <?php echo date('d/m/Y', strtotime($student['created_at'])); ?>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium" onclick="event.stopPropagation()">
                    <a href="view_student.php?id=<?php echo $student['id']; ?>" 
                       class="text-blue-600 hover:text-blue-900 mr-3">View</a>
                    <?php if ($student['payment_status'] === 'paid'): ?>
                      <a href="verify_payment.php?id=<?php echo $student['id']; ?>" 
                         class="text-green-600 hover:text-green-900">Verify</a>
                    <?php endif; ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            <?php else: ?>
              <tr>
                <td colspan="11" class="px-6 py-12 text-center text-gray-500">
                  <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                  </svg>
                  <p class="mt-2">No applications found</p>
                </td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</body>
</html>
