<?php
// متغيرات لتخزين النتيجة والجدول
$result = "";
$tableHtml = "";

// هل تم إرسال النموذج؟
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    
    // استقبال البيانات من النموذج
    $courses = $_POST['course'] ?? [];
    $credits = $_POST['credits'] ?? [];
    $grades = $_POST['grade'] ?? [];
    
    $totalPoints = 0;
    $totalCredits = 0;
    
    // بناء جدول النتائج
    $tableHtml = "<h2>Course Summary</h2>";
    $tableHtml .= "<table border='1'>";
    $tableHtml .= "<tr><th>Course</th><th>Credits</th><th>Grade</th><th>Grade Points</th></tr>";
    
    for ($i = 0; $i < count($courses); $i++) {
        $course = htmlspecialchars($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);
        
        if ($cr <= 0) continue;
        
        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;
        
        $tableHtml .= "<tr>";
        $tableHtml .= "<td>$course</td>";
        $tableHtml .= "<td>$cr</td>";
        $tableHtml .= "<td>$g</td>";
        $tableHtml .= "<td>$pts</td>";
        $tableHtml .= "</tr>";
    }
    $tableHtml .= "</table>";
    
    // حساب المعدل
    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        if ($gpa >= 3.7) {
            $interpretation = "Distinction";
        } elseif ($gpa >= 3.0) {
            $interpretation = "Merit";
        } elseif ($gpa >= 2.0) {
            $interpretation = "Pass";
        } else {
            $interpretation = "Fail";
        }
        
        $result = "Your GPA is <strong>" . number_format($gpa, 2) . "</strong> ($interpretation).";
    } else {
        $result = "No valid courses entered.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    <h1>GPA Calculator</h1>
    
    <!-- عرض النتيجة والجدول إذا وجدا -->
    <?php if ($result != ""): ?>
        <?php echo $tableHtml; ?>
        <p><strong><?php echo $result; ?></strong></p>
        <hr>
    <?php endif; ?>
    
    <!-- النموذج (نفس الكود من index.html) -->
    <form action="" method="post" onsubmit="return validateForm();">
        <div id="courses">
            <div class="course-row">
                <label>Course:</label>
                <input type="text" name="course[]" placeholder="e.g. Mathematics" required>
                
                <label>Credits:</label>
                <input type="number" name="credits[]" placeholder="e.g. 3" min="1" required>
                
                <label>Grade:</label>
                <select name="grade[]">
                    <option value="4.0">A</option>
                    <option value="3.0">B</option>
                    <option value="2.0">C</option>
                    <option value="1.0">D</option>
                    <option value="0.0">F</option>
                </select>
            </div>
        </div>
        
        <button type="button" onclick="addCourse()">+ Add Course</button><br><br>
        <input type="submit" value="Calculate GPA">
    </form>
</body>
</html>
