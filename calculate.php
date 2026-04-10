<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $courses = $_POST['course'] ?? [];
    $credits = $_POST['credits'] ?? [];
    $grades = $_POST['grade'] ?? [];
    $previousGPA = floatval($_POST['previousGPA'] ?? 0);
    $previousCredits = floatval($_POST['previousCredits'] ?? 0);
    
    $totalPoints = 0;
    $totalCredits = 0;
    $courseDetails = [];
    $errors = [];
    
    if (empty($courses)) {
        $errors[] = "No courses entered.";
    }
    
    for ($i = 0; $i < count($courses); $i++) {
        $course = trim($courses[$i]);
        $cr = floatval($credits[$i]);
        $g = floatval($grades[$i]);
        
        if (empty($course)) {
            $errors[] = "Course name is empty in row " . ($i+1);
        }
        if ($cr <= 0) {
            $errors[] = "Credits for '" . htmlspecialchars($course) . "' must be greater than 0.";
        }
        
        $pts = $cr * $g;
        $totalPoints += $pts;
        $totalCredits += $cr;
        
        if ($g >= 3.7) $subjectEval = "Excellent";
        elseif ($g >= 3.0) $subjectEval = "Very Good";
        elseif ($g >= 2.0) $subjectEval = "Good";
        else $subjectEval = "Fail";
        
        $courseDetails[] = [
            'name' => htmlspecialchars($course),
            'credits' => $cr,
            'grade_point' => $g,
            'points' => $pts,
            'evaluation' => $subjectEval
        ];
    }
    
    if (!empty($errors)) {
        echo json_encode(['success' => false, 'errors' => $errors]);
        exit;
    }
    
    if ($totalCredits > 0) {
        $gpa = $totalPoints / $totalCredits;
        
        if ($gpa >= 3.7) $interpretation = "Distinction";
        elseif ($gpa >= 3.0) $interpretation = "Merit";
        elseif ($gpa >= 2.0) $interpretation = "Pass";
        else $interpretation = "Fail";
        
        $totalPointsAll = $totalPoints + ($previousGPA * $previousCredits);
        $totalCreditsAll = $totalCredits + $previousCredits;
        $cumulativeGPA = ($totalCreditsAll > 0) ? $totalPointsAll / $totalCreditsAll : 0;
        
        if ($cumulativeGPA >= 3.7) $cumulativeInterpretation = "Distinction";
        elseif ($cumulativeGPA >= 3.0) $cumulativeInterpretation = "Merit";
        elseif ($cumulativeGPA >= 2.0) $cumulativeInterpretation = "Pass";
        else $cumulativeInterpretation = "Fail";
        
        echo json_encode([
            'success' => true,
            'gpa' => number_format($gpa, 2),
            'interpretation' => $interpretation,
            'cumulativeGPA' => number_format($cumulativeGPA, 2),
            'cumulativeInterpretation' => $cumulativeInterpretation,
            'totalCredits' => $totalCredits,
            'totalPoints' => $totalPoints,
            'courseDetails' => $courseDetails,
            'previousGPA' => $previousGPA,
            'previousCredits' => $previousCredits
        ]);
    } else {
        echo json_encode(['success' => false, 'errors' => ['No valid courses entered.']]);
    }
} else {
    echo json_encode(['success' => false, 'errors' => ['Invalid request.']]);
}
?>
