<?php
session_start();
include 'db/config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $profile_picture = '';

    // Menangani unggahan foto profil
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $allowed_exts = ['jpg', 'jpeg', 'png'];
        $file_ext = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
        if (in_array($file_ext, $allowed_exts)) {
            $file_name = uniqid() . '.' . $file_ext;
            $upload_dir = 'uploads/profile_pictures/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $upload_dir . $file_name)) {
                $profile_picture = $file_name;
            }
        }
    }

    $sql = "INSERT INTO users (username, full_name, email, password, profile_picture) VALUES ('$username', '$full_name', '$email', '$password', '$profile_picture')";

    if ($connection->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
    $connection->close();
}
?>
<?php include 'includes/header.php'; ?>
<form action="register.php" method="post" enctype="multipart/form-data">
    <label for="username">Username:</label>
    <input type="text" name="username" id="username" required>
    <label for="full_name">Full Name:</label>
    <input type="text" name="full_name" id="full_name" required>
    <label for="email">Email:</label>
    <input type="email" name="email" id="email" required>
    <label for="password">Password:</label>
    <input type="password" name="password" id="password" required>
    <label for="profile_picture">Profile Picture:</label>
    <input type="file" name="profile_picture" id="profile_picture" accept="image/jpeg, image/png">
    <button type="submit">Register</button>
</form>
<?php include 'includes/footer.php'; ?>