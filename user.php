<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $social_media = $_POST['social_media'];
    $password = isset($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : NULL;

    // Perbaiki nama variabel
    $profile_picture = $_POST['current_profile_picture'];
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $upload_dir = 'uploads/profile_pictures/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $profile_picture = $upload_dir . basename($_FILES['profile_picture']['name']);
        move_uploaded_file($_FILES['profile_picture']['tmp_name'], $profile_picture);
    }

    // Ganti 'id' dengan nama kolom yang benar
    $update_query = "UPDATE users SET full_name='$full_name', email='$email', social_media='$social_media', profile_picture='$profile_picture'";
    if ($password) {
        $update_query .= ", password='$password'";
    }
    $update_query .= " WHERE user_id='$user_id'"; // Sesuaikan nama kolom

    if ($connection->query($update_query) === TRUE) {
        echo "Profile updated successfully!";
    } else {
        echo "Error updating profile: " . $connection->error;
    }
}

// Tambahkan pengecekan kesalahan
$sql = "SELECT username, full_name, email, profile_picture, social_media FROM users WHERE user_id = '$user_id'"; // Sesuaikan nama kolom
$result = $connection->query($sql);

if (!$result) {
    die("Error: " . $connection->error); // Tampilkan kesalahan query
}

$user = $result->fetch_assoc();

$connection->close();
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>User Profile</h2>
    <form action="user.php" method="post" enctype="multipart/form-data">
        <label for="username">Username:</label>
        <input type="text" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>

        <label for="full_name">Full Name:</label>
        <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>

        <label for="social_media">Social Media:</label>
        <input type="text" name="social_media" value="<?php echo htmlspecialchars($user['social_media']); ?>">

        <label for="profile_picture">Profile Picture:</label>
        <?php if ($user['profile_picture']): ?>
            <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="150">
        <?php else: ?>
            <p>No profile picture uploaded.</p>
        <?php endif; ?>
        <input type="file" name="profile_picture">
        <input type="hidden" name="current_profile_picture" value="<?php echo htmlspecialchars($user['profile_picture']); ?>">

        <label for="password">New Password:</label>
        <input type="password" name="password" placeholder="Leave blank to keep current password">

        <button type="submit">Update Profile</button>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
