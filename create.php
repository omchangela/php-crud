<?php
include 'db.php';
include 'navbar.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    // Validate inputs
    if (empty($name) || empty($email)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } else {
        // Handle image upload
        $image = $_FILES['image']['name'];
        $targetDir = "uploads/"; // Folder to store images
        $targetFile = $targetDir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $sql = "INSERT INTO users (name, email, image) VALUES ('$name', '$email', '$image')";
            if ($conn->query($sql)) {
                // Redirect to read.php after successful insertion
                header("Location: read.php");
                exit();
            } else {
                $error = "Database error: " . $conn->error;
            }
        } else {
            $error = "Failed to upload image.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create User</title>
    <style>
        .is-invalid {
            border-color: #dc3545;
        }
        .invalid-feedback {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Create New User</h2>
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <?php if (isset($error)): ?>
                <div class="alert alert-danger">
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <form method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light" id="createForm">
                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" >
                    <div class="invalid-feedback">Please enter a valid name.</div>
                </div>

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control">
                    <div class="invalid-feedback">Please enter a valid email address.</div>
                </div>

                <!-- Image Upload -->
                <div class="mb-3">
                    <label for="image" class="form-label">Upload Image:</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*">
                    <div class="invalid-feedback">Please upload an image.</div>
                </div>

                <!-- Image Preview -->
                <div class="mb-3 text-center">
                    <img id="imagePreview" src="#" alt="Selected Image" style="display:none; width:150px; height:150px; object-fit:cover; border-radius: 8px; border: 1px solid #ccc;">
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('createForm').addEventListener('submit', function (event) {
    let isValid = true;

    // Validate Name
    const nameInput = document.getElementById('name');
    if (nameInput.value.trim() === '') {
        nameInput.classList.add('is-invalid');
        isValid = false;
    } else {
        nameInput.classList.remove('is-invalid');
    }

    // Validate Email
    const emailInput = document.getElementById('email');
    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailPattern.test(emailInput.value.trim())) {
        emailInput.classList.add('is-invalid');
        isValid = false;
    } else {
        emailInput.classList.remove('is-invalid');
    }

    // Validate Image
    const imageInput = document.getElementById('image');
    if (imageInput.files.length === 0) {
        imageInput.classList.add('is-invalid');
        isValid = false;
    } else {
        imageInput.classList.remove('is-invalid');
    }

    // Prevent Form Submission if Validation Fails
    if (!isValid) {
        event.preventDefault();
    }
});

// Image Preview
function previewImage(event) {
    const image = document.getElementById('imagePreview');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.style.display = 'block';
}
</script>
</body>
</html>
