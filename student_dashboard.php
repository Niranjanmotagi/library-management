<?php
// Start the session and check if the student is logged in
session_start();

// Check if 'student_id' is set in the session
// if (!isset($_SESSION['student_id'])) {
//     // Redirect to the login page if not logged in
//     header("Location: login.php");
//     exit;
// }

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library_system";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch student data
$student_id = $_SESSION['student_id']; // Student ID from the session
$stmt = $conn->prepare("SELECT name, books_borrowed, overdue_books FROM students WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$student_data = $stmt->get_result()->fetch_assoc();

if (!$student_data) {
    echo "No student data found.";
    exit;
}

// Fetch borrowed books
$stmt_books = $conn->prepare("SELECT book_title, due_date FROM borrowed_books WHERE student_id = ?");
$stmt_books->bind_param("i", $student_id);
$stmt_books->execute();
$books_result = $stmt_books->get_result();

// Fetch notifications
$stmt_notifications = $conn->prepare("SELECT message, date FROM notifications WHERE student_id = ? ORDER BY date DESC");
$stmt_notifications->bind_param("i", $student_id);
$stmt_notifications->execute();
$notifications_result = $stmt_notifications->get_result();

// Fetch borrow history
$stmt_history = $conn->prepare("SELECT book_title, borrowed_date, returned_date FROM borrow_history WHERE student_id = ? ORDER BY borrowed_date DESC");
$stmt_history->bind_param("i", $student_id);
$stmt_history->execute();
$history_result = $stmt_history->get_result();

// Close prepared statements
$stmt->close();
$stmt_books->close();
$stmt_notifications->close();
$stmt_history->close();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Student Dashboard | BLDEACET Central Library</title>
    <link rel="stylesheet" href="student_dashboard.css">
    <link href="https://fonts.googleapis.com/css2?family=Arapey&family=Poppins:wght@400;500&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Sidebar Menu -->
    <nav class="sidebar">
        <div class="logo">
            <h3>BLDEACET Library</h3>
            <img src="Header.svg" alt="Library Image">
            <p>Department: Computer Science</p>
        </div>  
        <hr>  
        <ul>
            <li><a href="#search-books">Search Books</a></li>
            <li><a href="#dashboard" class="active">Dashboard</a></li>
            <li><a href="#notifications">Notifications</a></li>
            <li><a href="#my-books">My Books</a></li>
            <li><a href="#borrow-history">Borrow History</a></li>
            <li><a href="#help">Help & Support</a></li>
        </ul>
    </nav>        

    <!-- Main Page Content -->
    <div class="main-content">
        <!-- Search Books Section -->
        <section id="search-books">
            <h2><b>//</b> Search Books <span>&#10042;</span></h2>
            <p>Search for books in the library database.</p>
            <div class="search-container">
                <div class="search-bar">
                    <form method="POST" action="search_books.php">
                        <input type="text" name="search_query" id="searchInput" placeholder="&#10024; Search Course Books and More..." aria-label="Search Course Books">
                        <button type="submit">Search</button>
                    </form>
                </div>
            </div>
        </section>
        
        <!-- Dashboard Section -->
        <section id="dashboard">
            <h2><b>//</b> Dashboard <span>&#10042;</span></h2>
            <p>Welcome back, <?php echo htmlspecialchars($student_data['name']); ?>! Here is an overview of your library activity.</p>
            <div class="stats">
                <div class="stat-card">
                    <h3>Books Borrowed</h3>
                    <p><?php echo htmlspecialchars($student_data['books_borrowed']); ?></p>
                </div>
                <div class="stat-card">
                    <h3>Overdue Books</h3>
                    <p><?php echo htmlspecialchars($student_data['overdue_books']); ?></p>
                </div>
            </div>
        </section>

        <!-- Notifications Section -->
        <section id="notifications">
            <h2><b>//</b> Notifications <span>&#10042;</span></h2>
            <p>Stay updated with the latest library updates and reminders.</p>
            <ul class="notification-list">
                <?php while ($row = $notifications_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($row['message']); ?> (<?php echo $row['date']; ?>)</li>
                <?php } ?>
            </ul>
        </section>

        <!-- My Books Section -->
        <section id="my-books">
            <h2><b>//</b> My Books <span>&#10042;</span></h2>
            <p>Here are the books currently borrowed by you.</p>
            <div class="book-list">
                <?php while ($row = $books_result->fetch_assoc()) { ?>
                    <div class="book-card">
                        <h3><?php echo htmlspecialchars($row['book_title']); ?></h3>
                        <p>Due Date: <?php echo $row['due_date']; ?></p>
                    </div>
                <?php } ?>
            </div>
        </section>

        <!-- Borrow History Section -->
        <section id="borrow-history">
            <h2><b>//</b> Borrow History <span>&#10042;</span></h2>
            <p>Check your past borrow history.</p>
            <ul class="history-list">
                <?php while ($history = $history_result->fetch_assoc()) { ?>
                    <li><?php echo htmlspecialchars($history['book_title']); ?>: Borrowed on <?php echo $history['borrowed_date']; ?>, Returned on <?php echo $history['returned_date'] ?: "Not Returned Yet"; ?></li>
                <?php } ?>
            </ul>
        </section>

        <!-- Help Section -->
        <section id="help">
            <h2><b>//</b> Help & Support <span>&#10042;</span></h2>
            <p>Contact the librarian or access the FAQ for assistance.</p>
        </section>
    </div>

    <!-- Back to Top Button -->
    <button class="back-to-top" onclick="window.scrollTo(0, 0);">Back to Top</button>

    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const target = document.getElementById(targetId);

                if (target) {
                    target.scrollIntoView({ behavior: 'smooth' });
                    history.replaceState(null, null, `#${targetId}`);
                }
            });
        });
    </script>
</body>
</html>
