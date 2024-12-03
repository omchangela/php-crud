<?php
include 'db.php';
include 'navbar.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM users WHERE id = $id";
    $result = $conn->query($sql);
    $user = $result->fetch_assoc();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Handle image upload
    $image = $user['image']; // Default to existing image if no new one is uploaded
    if (!empty($_FILES['image']['name'])) {
        $targetDir = "uploads/";
        $targetFile = $targetDir . basename($_FILES['image']['name']);
        if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
            $image = $_FILES['image']['name']; // Update with new image name
        } else {
            echo "<div class='alert alert-danger'>Error uploading the image.</div>";
        }
    }

    // Update user details
    $sql = "UPDATE users SET name = '$name', email = '$email', image = '$image' WHERE id = $id";
    if ($conn->query($sql)) {
        header("Location: read.php"); // Redirect after successful update
        exit();
    } else {
        echo "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Update User</title>
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center mb-4">Update User</h2>
    <div class="row d-flex justify-content-center">
        <div class="col-12 col-md-6 col-lg-4">
            <form method="POST" enctype="multipart/form-data" class="shadow p-4 rounded bg-light">
                <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

                <!-- Name Input -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo $user['name']; ?>" required>
                </div>

                <!-- Email Input -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" id="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
                </div>

                <!-- Current Image Display -->
                <div class="mb-3 text-center">
                    <label class="form-label">Current Image:</label><br>
                    <?php if (!empty($user['image'])): ?>
                        <img src="uploads/<?php echo $user['image']; ?>" alt="User Image" style="width:150px; height:150px; object-fit:cover; border-radius:8px; border:1px solid #ccc;"><br>
                    <?php else: ?>
                        <span>No Image</span><br>
                    <?php endif; ?>
                </div>

                <!-- New Image Upload -->
                <div class="mb-3">
                    <label for="image" class="form-label">Upload New Image (optional):</label>
                    <input type="file" id="image" name="image" class="form-control" accept="image/*" onchange="previewImage(event)">
                </div>

                <!-- Image Preview -->
                <div class="mb-3 text-center">
                    <img id="imagePreview" src="#" alt="Selected Image" style="display:none; width:150px; height:150px; object-fit:cover; border-radius: 8px; border: 1px solid #ccc;">
                </div>

                <!-- Submit Button -->
                <div class="text-center">
                    <button type="submit" class="btn btn-success w-100">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
function previewImage(event) {
    const image = document.getElementById('imagePreview');
    image.src = URL.createObjectURL(event.target.files[0]);
    image.style.display = 'block';
}
</script>
</body>
</html>
