<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if (!isset($_GET['username'])) {
    echo "Username not provided.";
    exit;
}

$username = $connection->real_escape_string($_GET['username']);
$sql = "SELECT * FROM users WHERE username = ?";
$stmt = $connection->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$connection->close();
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>User Details</h2>
    <?php if ($user): ?>
        <div class="user-profile">
            <h3><?php echo htmlspecialchars($user['full_name']); ?></h3>
            <p>Username: <?php echo htmlspecialchars($user['username']); ?></p>
            <p>Email: <?php echo htmlspecialchars($user['email']); ?></p>
            <?php if ($user['profile_picture']): ?>
                <img src="uploads/profile_pictures/<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture" width="150">
            <?php else: ?>
                <p>No profile picture uploaded.</p>
            <?php endif; ?>
            <p>Social Media: <?php echo htmlspecialchars($user['social_media']); ?></p>
        </div>
    <?php else: ?>
        <p>User not found.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
