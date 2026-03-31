<?php
header('Content-Type: application/json');

if (isset($_POST['course'], $_POST['credits'], $_POST['grade'])) {

    $courses = $_POST['course'];
    $credits = $_POST['credits'];
    $grades  = $_POST['grade'];

    $totalCredits = 0;
    $totalPoints  = 0;

    $tableHtml = '<table class="table table-bordered mt-3">
    <thead class="thead-dark">
        <tr>
            <th>Course</th>
            <th>Credits</th>
            <th>Grade</th>
            <th>Points</th>
        </tr>
    </thead>
    <tbody>';

    for ($i = 0; $i < count($courses); $i++) {

        $course = htmlspecialchars($courses[$i]);
        $cr     = floatval($credits[$i]);
        $g      = floatval($grades[$i]);

        if ($cr <= 0) continue;

        $points = $cr * $g;

        $totalPoints  += $points;
        $totalCredits += $cr;

        $tableHtml .= "<tr>
            <td>$course</td>
            <td>$cr</td>
            <td>$g</td>
            <td>$points</td>
        </tr>";
    }

    $tableHtml .= '</tbody></table>';

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

        $message = "Your GPA is " . number_format($gpa, 2) . " ($interpretation)";

        echo json_encode([
            'success' => true,
            'gpa' => $gpa,
            'message' => $message,
            'tableHtml' => $tableHtml
        ]);

    } else {

        echo json_encode([
            'success' => false,
            'message' => 'No valid courses entered.'
        ]);
    }

} else {

    echo json_encode([
        'success' => false,
        'message' => 'Data not received.'
    ]);
}

exit;
?>
