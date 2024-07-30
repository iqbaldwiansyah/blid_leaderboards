<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BLID Leaderboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<header>
    <div class="navbar">
        <a href="index.php" class="logo">BLID Leaderboard</a>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="submit.php">Submit</a></li>
                <li><a href="game_details.php">Download</a></li>
            </ul>
        </nav>
        <div class="auth-buttons">
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }

            if(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true){
                echo '<a href="user.php" class="btn">Profile</a>';
                echo '<a href="logout.php" class="btn">Logout</a>';
            } else {
                echo '<a href="#" class="btn" onclick="showLogin()">Login</a>';
            }
            ?>
        </div>
    </div>
</header>
<div id="loginModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeLogin()">&times;</span>
        <form action="login.php" method="post">
            <h2>Login</h2>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
            <p>Belum punya akun? <a href="register.php">Register disini</a></p>
        </form>
    </div>
</div>
</body>
</html>
