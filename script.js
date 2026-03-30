 function addCourse() {
2 var row = document.createElement(’div’);
3 row.className = ’course-row’;
4 row.innerHTML =
5 ’<label>Course:</label>’ +
6 ’<input type="text" name="course[]" ’ +
7 ’placeholder="e.g. Mathematics" required>’ +
8 ’<label>Credits:</label>’ +
9 ’<input type="number" name="credits[]" ’ +
10 ’placeholder="e.g. 3" min="1" required>’ +
11 ’<label>Grade:</label>’ +
12 ’<select name="grade[]">’ +
 ’<option value="4.0">A</option>’ +
14 ’<option value="3.0">B</option>’ +
15 ’<option value="2.0">C</option>’ +
16 ’<option value="1.0">D</option>’ +
17 ’<option value="0.0">F</option>’ +
18 ’</select>’ +
19 ’<button type="button" ’ +
20 ’onclick="this.parentNode.remove()">Remove</button>’;
21 document.getElementById(’courses’).appendChild(row);
22 }
23
24 function validateForm() {
25 var courses = document.querySelectorAll(’[name="course[]"]’);
26 var credits = document.querySelectorAll(’[name="credits[]"]’);
27
28 for (var i = 0; i < courses.length; i++) {
29 if (courses[i].value === "") {
30 alert("All course name fields are required.");
31 return false;
32 }
33 }
34 for (var j = 0; j < credits.length; j++) {
35 if (isNaN(credits[j].value) || credits[j].value <= 0) {
36 alert("Credit hours must be positive numbers.");
37 return false;
38 }
39 }
40 return true;
41 }


