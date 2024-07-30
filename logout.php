<?php
session_start();

// Menghapus semua variabel sesi
$_SESSION = array();

// Jika menggunakan cookie sesi, hapus cookie sesi
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, 
        $params["path"], 
        $params["domain"], 
        $params["secure"], 
        $params["httponly"]
    );
}

// Hapus sesi
session_destroy();

// Arahkan pengguna ke halaman login setelah logout
header("Location: login.php");
exit;
?>
