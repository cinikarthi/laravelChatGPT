<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student List</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow p-4">
    
        <h2 class="text-center mb-4">Student List</h2>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Standard</th>
                    <th>Section</th>
                    <th>Joining Date</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="studentTableBody">
                <!-- Student data will be appended here dynamically -->
            </tbody>
        </table>

        <p id="noDataMessage" class="text-center text-muted d-none">No students found.</p>

        <!-- Pagination Controls -->
        <nav>
            <ul class="pagination justify-content-center" id="paginationControls"></ul>
        </nav>
    </div>
</div>

<script>
    let currentPage = 1;

    document.addEventListener("DOMContentLoaded", function() {
        fetchStudents(currentPage);
    });

    function fetchStudents(page) {
        fetch(`/students-data?page=${page}`)
            .then(response => response.json())
            .then(data => {
                let tableBody = document.getElementById("studentTableBody");
                let paginationControls = document.getElementById("paginationControls");
                let noDataMessage = document.getElementById("noDataMessage");

                tableBody.innerHTML = ""; // Clear previous data
                paginationControls.innerHTML = ""; // Clear pagination buttons

                if (data.data.length > 0) {
                    noDataMessage.classList.add("d-none");

                    data.data.forEach(student => {
                        let row = `
                            <tr>
                                <td>${student.id}</td>
                                <td>${student.name}</td>
                                <td>${student.email}</td>
                                <td>${student.phone}</td>
                                <td>${student.standard}</td>
                                <td>${student.section}</td>
                                <td>${new Date(student.created_at).toLocaleString()}</td>
                                <td><button onclick="deleteStudent(${student.id})" class="btn btn-danger">Delete</button></td>
                            </tr>
                        `;
                        tableBody.innerHTML += row;
                    });

                    // Generate Pagination Buttons
                    for (let i = 1; i <= data.last_page; i++) {
                        paginationControls.innerHTML += `
                            <li class="page-item ${i === data.current_page ? 'active' : ''}">
                                <button class="page-link" onclick="fetchStudents(${i})">${i}</button>
                            </li>
                        `;
                    }
                } else {
                    noDataMessage.classList.remove("d-none");
                }
            })
            .catch(error => console.error("Error fetching students:", error));
    }

    function deleteStudent(studentId){
         let row = document.getElementById("row-" + studentId);

        if (confirm("Are you sure you want to delete this student?")) {
            fetch(`/delete-student/${studentId}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json"
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.success) {
                   location.reload();
                }
            }) .catch(error => {
                alert("Error deleting student!");
                console.error("Error:", error);
            });
           
        }
    }
</script>

</body>
</html>
