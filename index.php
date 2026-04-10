<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>GPA Calculator - Advanced</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .card-custom {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            padding: 30px;
            margin-bottom: 20px;
        }
        .course-row {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 15px;
            transition: all 0.3s;
        }
        .course-row:hover {
            background: #e9ecef;
            transform: scale(1.01);
        }
        .btn-remove {
            margin-top: 32px;
        }
        .help-section {
            background: #e7f3ff;
            border-left: 5px solid #007bff;
            padding: 15px;
            border-radius: 10px;
        }
        .table-custom th {
            background: #4a5568;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card-custom">
        <h1 class="text-center mb-4">
            <i class="fas fa-calculator"></i> GPA Calculator
        </h1>
        
        <div id="resultArea" style="display: none;">
            <div id="resultMessage" class="alert"></div>
            <div id="courseTable"></div>
            <div id="cumulativeArea" class="alert alert-info mt-3"></div>
            <button id="exportPDF" class="btn btn-danger mt-2">
                <i class="fas fa-file-pdf"></i> Export to PDF
            </button>
            <button id="exportImage" class="btn btn-success mt-2">
                <i class="fas fa-image"></i> Export as Image
            </button>
            <hr>
        </div>
        
        <form id="gpaForm">
            <div id="coursesContainer">
                <div class="course-row">
                    <div class="row">
                        <div class="col-md-5">
                            <label><i class="fas fa-book"></i> Course Name</label>
                            <input type="text" name="course[]" class="form-control" placeholder="e.g. Mathematics" required>
                        </div>
                        <div class="col-md-3">
                            <label><i class="fas fa-clock"></i> Credits</label>
                            <input type="number" name="credits[]" class="form-control" placeholder="e.g. 3" min="1" required>
                        </div>
                        <div class="col-md-3">
                            <label><i class="fas fa-star"></i> Grade</label>
                            <select name="grade[]" class="form-control">
                                <option value="4.0">A / A+ (4.0)</option>
                                <option value="3.0">B (3.0)</option>
                                <option value="2.0">C (2.0)</option>
                                <option value="1.0">D (1.0)</option>
                                <option value="0.0">F (0.0)</option>
                            </select>
                        </div>
                        <div class="col-md-1">
                            <button type="button" class="btn btn-danger btn-remove" onclick="removeRow(this)"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            
            <button type="button" id="addCourseBtn" class="btn btn-secondary mb-3">
                <i class="fas fa-plus"></i> Add Course
            </button>
            
            <div class="row mt-3">
                <div class="col-md-6">
                    <label><i class="fas fa-chart-line"></i> Previous GPA (Optional)</label>
                    <input type="number" id="previousGPA" class="form-control" step="0.01" min="0" max="4" placeholder="e.g. 3.2">
                </div>
                <div class="col-md-6">
                    <label><i class="fas fa-hourglass-half"></i> Previous Credits (Optional)</label>
                    <input type="number" id="previousCredits" class="form-control" min="0" placeholder="e.g. 60">
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary mt-3 w-100">
                <i class="fas fa-chart-simple"></i> Calculate GPA
            </button>
        </form>
    </div>
    
    <div class="card-custom help-section">
        <h5><i class="fas fa-info-circle"></i> Help & Instructions</h5>
        <ul>
            <li><strong>Distinction:</strong> GPA ≥ 3.7</li>
            <li><strong>Merit:</strong> 3.0 ≤ GPA < 3.7</li>
            <li><strong>Pass:</strong> 2.0 ≤ GPA < 3.0</li>
            <li><strong>Fail:</strong> GPA < 2.0</li>
            <li>You can add unlimited courses</li>
            <li>Data is automatically saved in your browser (localStorage)</li>
        </ul>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="script.js"></script>
</body>
</html>
