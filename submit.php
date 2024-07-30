<?php
session_start();
include 'db/config.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category = $_POST['category'];
    $sub_category = isset($_POST['sub_category']) ? $_POST['sub_category'] : NULL;
    $hours = (int)$_POST['hours'];
    $minutes = (int)$_POST['minutes'];
    $seconds = (int)$_POST['seconds'];
    $milliseconds = (int)$_POST['milliseconds'];
    $best_time = sprintf("%02d:%02d:%02d:%03d", $hours, $minutes, $seconds, $milliseconds);
    $youtube_link = $_POST['youtube_link'];
    $submission_date = $_POST['submission_date'];
    $description = $connection->real_escape_string($_POST['description']);
    $user_id = $_SESSION['user_id'];
    $car_used = $connection->real_escape_string($_POST['car_used']);

    $sql = "INSERT INTO leaderboards (category, sub_category, user_id, best_time, car_used, youtube_link, submission_date, description)
            VALUES ('$category', '$sub_category', '$user_id', '$best_time', '$car_used', '$youtube_link', '$submission_date', '$description')";

    if ($connection->query($sql) === TRUE) {
        echo "Score submitted successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
}

$connection->close();
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>Submit Your Best Time</h2>
    <form action="submit.php" method="post">
        <label for="category">Category:</label>
        <select name="category" id="category" required onchange="toggleSubCategory()">
            <option value="Full Game">Full Game</option>
            <option value="Circuit 1">Circuit 1</option>
            <option value="Sprint 1">Sprint 1</option>
        </select>

        <div id="subCategorySection">
            <label for="sub_category">Sub-Category:</label>
            <select name="sub_category" id="sub_category">
                <option value="Career Any%">Career Any%</option>
                <option value="Career 100%">Career 100%</option>
                <option value="Game 100%">Game 100%</option>
            </select>
        </div>

        <label for="hours">Best Time (h:m:s:ms):</label>
        <div class="time-input">
            <input type="number" name="hours" id="hours" placeholder="hh" min="0" required>
            <span>:</span>
            <input type="number" name="minutes" id="minutes" placeholder="mm" min="0" max="59" required>
            <span>:</span>
            <input type="number" name="seconds" id="seconds" placeholder="ss" min="0" max="59" required>
            <span>:</span>
            <input type="number" name="milliseconds" id="milliseconds" placeholder="ms" min="0" max="999" required>
        </div>

        <label for="car_used">Car Used:</label>
        <input type="text" name="car_used" id="car_used" required>

        <label for="youtube_link">YouTube Video URL:</label>
        <input type="url" name="youtube_link" id="youtube_link" required>

        <label for="submission_date">Date Achieved:</label>
        <input type="date" name="submission_date" id="submission_date" required>

        <label for="description">Description:</label>
        <textarea name="description" id="description" rows="4"></textarea>

        <p>Data best time yang kamu inputkan akan dilihat terlebih dahulu oleh moderator. Jika sesuai kategori, akan tampil di halaman utama. Jika ditolak, kamu dapat melihat pesan dari moderator.</p>
        <button type="submit">Submit</button>
    </form>
</div>

<script>
function toggleSubCategory() {
    var category = document.getElementById('category').value;
    var subCategorySection = document.getElementById('subCategorySection');
    if (category === 'Full Game') {
        subCategorySection.style.display = 'block';
    } else {
        subCategorySection.style.display = 'none';
    }
}

// Panggil fungsi ini untuk memastikan subkategori tampil saat halaman dimuat
document.addEventListener('DOMContentLoaded', (event) => {
    toggleSubCategory();
});
</script>

<?php include 'includes/footer.php'; ?>
