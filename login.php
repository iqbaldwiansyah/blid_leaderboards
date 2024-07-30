<?php
session_start();
include 'db/config.php';

// Tampilkan kesalahan jika ada
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $connection->real_escape_string(trim($_POST['username']));
    $password = $connection->real_escape_string(trim($_POST['password']));

    $sql = "SELECT user_id, username, password, role FROM users WHERE username = '$username'";
    $result = $connection->query($sql);

    if ($result === FALSE) {
        echo "Error in query: " . $connection->error;
    } else {
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                $_SESSION['loggedin'] = true;
                $_SESSION['user_id'] = $row['user_id'];
                $_SESSION['username'] = $row['username'];
                $_SESSION['role'] = $row['role']; // Simpan peran di session
                header("location: index.php");
                exit; // Pastikan untuk menghentikan eksekusi setelah redirect
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No account found with that username.";
        }
    }
}

$connection->close();
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>Login</h2>
    <form action="login.php" method="post">
        <input type="text" name="username" placeholder="Username" required>
        <input type="password" name="password" placeholder="Password" required>
        <button type="submit">Login</button>
        <p>Belum punya akun? <a href="register.php">Register disini</a></p>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
