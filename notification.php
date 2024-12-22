<?php 
// Include the connection to your database
include('db_connect.php');

// Fetch notifications (name, email, message) from the contact_messages table
$query = "SELECT cm.name, cm.email, cm.message FROM contact_messages cm ORDER BY cm.id DESC"; 
$result = mysqli_query($conn, $query);

// Check for errors in the query
if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

// Check if any rows were returned
if (mysqli_num_rows($result) > 0) {
    // Display notifications
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<div class='notification'>";
        echo "<h4>New message from " . htmlspecialchars($row['name']) . "</h4>";
        echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
        echo "<p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>";
        echo "</div>";
    }
} else {
    echo "<p>No new messages found.</p>"; // Display this if no messages are found
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="bldea_logo.webp" type="image/x-icon">
    <title>Notifications | BLDEACET Central Library</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&family=Arapey&display=swap" rel="stylesheet">
    <style>
        /* General Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        /* Body Styling */
        body {
            background: #f5f5f5;
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0;
            margin: 0;
            overflow-y: auto;
        }

        /* Page Heading */
        .page-heading {
            width: 100%;
            text-align: center;
            font-weight: 100;
            font-size: 1rem;
            margin-bottom: 30px;
            color: #ccc;
        }

        /* Notifications Container */
        .notifications-container {
            width: 100%;
            padding: 20px;
            max-width: 800px;
            margin-top: 100px; /* Adjusted padding-top to margin-top */
        }

        /* Notification Styles */
        .notification {
            background: #222831;
            padding: 15px;
            padding-left: 50px;
            padding-right: 900px;
            margin: 15px 0;
            border-radius: 5px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .notification:hover {
            box-shadow: 0 12px 25px rgba(0, 0, 0, 0.2);
        }

        .notification h4 {
            font-family: 'Arapey', serif;
            font-weight: 300;
            font-size: 1.5rem;
            color: #fff;
        }

        .notification p {
            font-size: 1rem;
            color: #ccc;
            margin-top: 10px;
        }

        .notification p strong {
            color: #e587df; /* Highlight strong labels */
        }

        /* Responsive Styling */
        @media (max-width: 768px) {
            .notification {
                width: 100%;
                margin: 10px 0;
            }

            .page-heading {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="notifications-container">
        <h1 class="page-heading">You are all Catch up </h1>
        <?php
        // Display notifications from the database
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<div class='notification'>";
            echo "<h4>New message from " . htmlspecialchars($row['name']) . "</h4>";
            echo "<p><strong>Email:</strong> " . htmlspecialchars($row['email']) . "</p>";
            echo "<p><strong>Message:</strong> " . htmlspecialchars($row['message']) . "</p>";
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>
