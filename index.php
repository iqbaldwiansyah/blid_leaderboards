<?php include 'db/config.php'; ?>
<?php include 'includes/header.php'; ?>

<div class="main-content">
    <h1>Leaderboard</h1>

    <!-- Check if user is logged in and is a moderator -->
    <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true): ?>
        <?php
        $user_id = $_SESSION['user_id'];
        $sql = "SELECT role FROM users WHERE user_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        if ($user['role'] == 'moderator'):
        ?>
            <!-- Moderator Button -->
            <div class="moderator-button">
                <a href="moderator.php" class="btn">Moderator</a>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <!-- Category Selection -->
    <div class="category-selection">
        <label for="category">Select Category:</label>
        <select id="category" name="category" onchange="updateSubcategories()">
            <option value="">-- Select Category --</option>
            <option value="Full Game" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Full Game') ? 'selected' : ''; ?>>Full Game</option>
            <option value="Circuit 1" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Circuit 1') ? 'selected' : ''; ?>>Circuit 1</option>
            <option value="Sprint 1" <?php echo (isset($_GET['category']) && $_GET['category'] == 'Sprint 1') ? 'selected' : ''; ?>>Sprint 1</option>
        </select>
        
        <!-- Subcategory Buttons -->
        <div id="subcategories" style="<?php echo (isset($_GET['category']) && $_GET['category'] == 'Full Game') ? 'display:block;' : 'display:none;'; ?>">
            <button class="subcategory-btn" onclick="filterBySubcategory('Career Any%')">Career Any%</button>
            <button class="subcategory-btn" onclick="filterBySubcategory('Career 100%')">Career 100%</button>
            <button class="subcategory-btn" onclick="filterBySubcategory('Game 100%')">Game 100%</button>
        </div>
    </div>

    <!-- Search Bar -->
    <div class="search-bar">
        <input type="text" id="search" placeholder="Search for users..." onkeyup="searchUsers()">
    </div>

    <!-- Leaderboard Table -->
    <div class="leaderboard">
        <table>
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Username</th>
                    <th>Best Time</th>
                    <th>Car Used</th>
                    <th>Watch</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body">
                <?php
                $category = isset($_GET['category']) ? $connection->real_escape_string($_GET['category']) : '';
                $subcategory = isset($_GET['subcategory']) ? $connection->real_escape_string($_GET['subcategory']) : '';

                // Build the SQL query
                $sql = "SELECT u.username, u.profile_picture, l.best_time, l.car_used, l.youtube_link
                        FROM leaderboards l
                        JOIN users u ON l.user_id = u.user_id
                        WHERE l.approved = 1";

                if ($category) {
                    $sql .= " AND l.category = '$category'";
                }
                if ($subcategory) {
                    $sql .= " AND l.sub_category = '$subcategory'";
                }

                $sql .= " ORDER BY l.best_time ASC";
                $result = $connection->query($sql);
                
                if (!$result) {
                    echo "<tr><td colspan='5'>Error executing query: " . $connection->error . "</td></tr>";
                } elseif ($result->num_rows > 0) {
                    $rank = 1;
                    while ($row = $result->fetch_assoc()) {
                        $color = '';
                        if ($rank == 1) {
                            $color = 'gold';
                        } elseif ($rank == 2) {
                            $color = 'silver';
                        } elseif ($rank == 3) {
                            $color = '#cc3300';
                        }
                        $profilePic = htmlspecialchars($row['profile_picture']);
                        $profilePicPath = $profilePic ? "" . htmlspecialchars($profilePic) : "images/BLIDICON.png";
                        // Debugging output
                        echo "<tr>
                                <td>" . $rank . "</td>
                                <td><img src='" . $profilePicPath . "' alt='Profile Picture' width='30' height='30' style='border-radius: 50%; margin-right: 10px;'> <a href='user_profile.php?username=" . htmlspecialchars($row['username']) . "' style='color: $color;'>" . htmlspecialchars($row['username']) . "</a></td>
                                <td>" . htmlspecialchars($row['best_time']) . "</td>
                                <td>" . htmlspecialchars($row['car_used']) . "</td>
                                <td><a href='" . htmlspecialchars($row['youtube_link']) . "' target='_blank'>Watch</a></td>
                              </tr>";
                        $rank++;
                    }
                } else {
                    echo "<tr><td colspan='5'>No records found</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<script>
function updateSubcategories() {
    var category = document.getElementById('category').value;
    var subcategoriesDiv = document.getElementById('subcategories');

    if (category === 'Full Game') {
        subcategoriesDiv.style.display = 'block';
    } else {
        subcategoriesDiv.style.display = 'none';
        // Reset subcategory filter if category is not 'Full Game'
        window.location.href = "index.php?category=" + category;
    }
}

function filterBySubcategory(subcategory) {
    var category = document.getElementById('category').value;
    window.location.href = "index.php?category=" + category + "&subcategory=" + encodeURIComponent(subcategory);
}

function searchUsers() {
    var input, filter, table, tr, td, i, txtValue;
    input = document.getElementById("search");
    filter = input.value.toUpperCase();
    table = document.getElementById("leaderboard-body");
    tr = table.getElementsByTagName("tr");

    for (i = 0; i < tr.length; i++) {
        td = tr[i].getElementsByTagName("td")[1]; // Username is in the second column (index 1)
        if (td) {
            txtValue = td.textContent || td.innerText;
            if (txtValue.toUpperCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }       
    }
}
</script>

<?php include 'includes/footer.php'; ?>
