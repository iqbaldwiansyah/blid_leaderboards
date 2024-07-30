<?php
session_start();
include 'db/config.php';

// Cek apakah pengguna adalah moderator
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'moderator') {
    header("location: login.php");
    exit;
}

// Proses tindakan approve atau reject
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $leaderboard_id = $_POST['leaderboard_id'];

    if (isset($_POST['approve'])) {
        $sql = "UPDATE leaderboards SET approved = 1, approved_by = " . $_SESSION['user_id'] . " WHERE leaderboard_id = '$leaderboard_id'";
    } elseif (isset($_POST['reject'])) {
        $reject_reason = $connection->real_escape_string($_POST['reject_reason']);
        $sql = "UPDATE leaderboards SET approved = 0, reject_reason = '$reject_reason' WHERE leaderboard_id = '$leaderboard_id'";
    }

    if ($connection->query($sql) === TRUE) {
        echo "Action completed successfully!";
        header("Location: moderator.php"); // Redirect setelah berhasil
        exit;
    } else {
        echo "Error: " . $sql . "<br>" . $connection->error;
    }
}

// Ambil pengajuan yang belum diproses
$sql = "SELECT l.leaderboard_id, u.username, l.best_time, l.car_used, l.youtube_link, l.submission_date, l.description
        FROM leaderboards l
        JOIN users u ON l.user_id = u.user_id
        WHERE l.approved = 0"; // Mengambil hanya pengajuan yang belum disetujui
$result = $connection->query($sql);

if (!$result) {
    die("Error: " . $connection->error);
}

$connection->close();
?>

<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h2>Moderator Dashboard</h2>
    <div class="leaderboard-approval">
        <?php if ($result->num_rows > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Best Time</th>
                    <th>Car Used</th>
                    <th>Video</th>
                    <th>Date Submitted</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['best_time']); ?></td>
                    <td><?php echo htmlspecialchars($row['car_used']); ?></td>
                    <td><a href="<?php echo htmlspecialchars($row['youtube_link']); ?>" target="_blank">Watch</a></td>
                    <td><?php echo htmlspecialchars($row['submission_date']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td>
                        <form method="post" action="moderator.php">
                            <input type="hidden" name="leaderboard_id" value="<?php echo htmlspecialchars($row['leaderboard_id']); ?>">
                            <button type="submit" name="approve">Approve</button>
                            <textarea name="reject_reason" placeholder="Reject Reason" rows="3"></textarea>
                            <button type="submit" name="reject">Reject</button>
                        </form>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No pending submissions.</p>
        <?php endif; ?>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
