$(document).ready(function() {
    loadSavedData();
    
    $('#addCourseBtn').click(function() {
        var newRow = $('.course-row').first().clone();
        newRow.find('input').val('');
        newRow.find('select').val('4.0');
        $('#coursesContainer').append(newRow);
        saveToLocalStorage();
    });
    
    $('#gpaForm').submit(function(e) {
        e.preventDefault();
        
        var formData = {
            course: [],
            credits: [],
            grade: [],
            previousGPA: $('#previousGPA').val() || 0,
            previousCredits: $('#previousCredits').val() || 0
        };
        
        $('.course-row').each(function() {
            var courseName = $(this).find('input[name="course[]"]').val();
            var credits = $(this).find('input[name="credits[]"]').val();
            var grade = $(this).find('select[name="grade[]"]').val();
            
            if (courseName && credits > 0) {
                formData.course.push(courseName);
                formData.credits.push(credits);
                formData.grade.push(grade);
            }
        });
        
        if (formData.course.length === 0) {
            alert('Please add at least one course');
            return;
        }
        
        $.ajax({
            url: 'calculate.php',
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    displayResult(response);
                    saveToLocalStorage();
                } else {
                    alert('Errors: ' + response.errors.join('\n'));
                }
            },
            error: function() {
                alert('Server error occurred');
            }
        });
    });
    
    $('#exportPDF').click(function() {
        var element = document.getElementById('resultArea');
        var opt = {
            margin: [0.5, 0.5, 0.5, 0.5],
            filename: 'GPA_Report.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2 },
            jsPDF: { unit: 'in', format: 'a4', orientation: 'portrait' }
        };
        html2pdf().set(opt).from(element).save();
    });
    
    $('#exportImage').click(function() {
        html2canvas(document.getElementById('resultArea')).then(canvas => {
            var link = document.createElement('a');
            link.download = 'GPA_Report.png';
            link.href = canvas.toDataURL();
            link.click();
        });
    });
    
    function saveToLocalStorage() {
        var coursesData = [];
        $('.course-row').each(function() {
            coursesData.push({
                course: $(this).find('input[name="course[]"]').val(),
                credits: $(this).find('input[name="credits[]"]').val(),
                grade: $(this).find('select[name="grade[]"]').val()
            });
        });
        localStorage.setItem('gpaCourses', JSON.stringify(coursesData));
        localStorage.setItem('previousGPA', $('#previousGPA').val());
        localStorage.setItem('previousCredits', $('#previousCredits').val());
    }
    
    function loadSavedData() {
        var saved = localStorage.getItem('gpaCourses');
        if (saved) {
            var courses = JSON.parse(saved);
            $('#coursesContainer').empty();
            courses.forEach(function(c, index) {
                var newRow = `
                    <div class="course-row">
                        <div class="row">
                            <div class="col-md-5">
                                <input type="text" name="course[]" class="form-control" value="${c.course || ''}" required>
                            </div>
                            <div class="col-md-3">
                                <input type="number" name="credits[]" class="form-control" value="${c.credits || ''}" min="1" required>
                            </div>
                            <div class="col-md-3">
                                <select name="grade[]" class="form-control">
                                    <option value="4.0" ${c.grade == '4.0' ? 'selected' : ''}>A (4.0)</option>
                                    <option value="3.0" ${c.grade == '3.0' ? 'selected' : ''}>B (3.0)</option>
                                    <option value="2.0" ${c.grade == '2.0' ? 'selected' : ''}>C (2.0)</option>
                                    <option value="1.0" ${c.grade == '1.0' ? 'selected' : ''}>D (1.0)</option>
                                    <option value="0.0" ${c.grade == '0.0' ? 'selected' : ''}>F (0.0)</option>
                                </select>
                            </div>
                            <div class="col-md-1">
                                <button type="button" class="btn btn-danger btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                            </div>
                        </div>
                    </div>
                `;
                $('#coursesContainer').append(newRow);
            });
        }
        
        var prevGPA = localStorage.getItem('previousGPA');
        var prevCred = localStorage.getItem('previousCredits');
        if (prevGPA) $('#previousGPA').val(prevGPA);
        if (prevCred) $('#previousCredits').val(prevCred);
    }
    
    function displayResult(data) {
        $('#resultArea').show();
        
        var alertClass = 'alert-success';
        if (data.gpa >= 3.7) alertClass = 'alert-success';
        else if (data.gpa >= 3.0) alertClass = 'alert-info';
        else if (data.gpa >= 2.0) alertClass = 'alert-warning';
        else alertClass = 'alert-danger';
        
        $('#resultMessage').removeClass().addClass('alert ' + alertClass);
        $('#resultMessage').html(`
            <h4><i class="fas fa-chart-line"></i> GPA: ${data.gpa} (${data.interpretation})</h4>
            <p><strong>Total Credits:</strong> ${data.totalCredits} | <strong>Total Points:</strong> ${data.totalPoints}</p>
        `);
        
        var tableHtml = `<table class="table table-bordered table-striped mt-3">
            <thead class="table-custom">
                <tr><th>Course</th><th>Credits</th><th>Grade Points</th><th>Points</th><th>Evaluation</th></tr>
            </thead>
            <tbody>`;
        data.courseDetails.forEach(c => {
            let evalClass = '';
            if (c.evaluation == 'Excellent') evalClass = 'table-success';
            else if (c.evaluation == 'Very Good') evalClass = 'table-info';
            else if (c.evaluation == 'Good') evalClass = 'table-warning';
            else evalClass = 'table-danger';
            tableHtml += `<tr class="${evalClass}">
                <td>${c.name}</td>
                <td>${c.credits}</td>
                <td>${c.grade_point}</td>
                <td>${c.points}</td>
                <td>${c.evaluation}</td>
            </tr>`;
        });
        tableHtml += `</tbody></table>`;
        $('#courseTable').html(tableHtml);
        
        $('#cumulativeArea').html(`
            <strong><i class="fas fa-chart-simple"></i> Cumulative GPA:</strong><br>
            ${data.cumulativeGPA} (${data.cumulativeInterpretation})<br>
            <small>Calculated with previous GPA: ${data.previousGPA} (${data.previousCredits} credits)</small>
        `);
    }
});

function removeRow(btn) {
    if ($('.course-row').length > 1) {
        $(btn).closest('.course-row').remove();
        var saveEvent = $.Event('click');
        $('#addCourseBtn').trigger(saveEvent);
    } else {
        alert('You must keep at least one course row');
    }
}
