<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="card shadow-lg p-4">
        <h2 class="text-center mb-4">Upload CSV File</h2>
        
        <form id="upload-form" enctype="multipart/form-data">
            <div class="mb-3">
                <input type="file" name="file" id="file" class="form-control" accept=".csv">
            </div>
            <button type="submit" class="btn btn-primary w-100">Upload</button>
        </form>

        <div id="message" class="mt-3"></div>
    </div>
</div>

<script>
    document.getElementById("upload-form").addEventListener("submit", function(event) {
        event.preventDefault();

        let formData = new FormData();
        let file = document.getElementById("file").files[0];

        if (!file) {
            document.getElementById("message").innerHTML = '<div class="alert alert-danger">Please select a CSV file.</div>';
            return;
        }

        formData.append("file", file);

        fetch("{{ url('/import-students') }}", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            }
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById("message").innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
        })
        .catch(error => {
            document.getElementById("message").innerHTML = '<div class="alert alert-danger">An error occurred while uploading.</div>';
        });
    });
</script>

</body>
</html>
