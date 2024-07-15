<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'tenant') {
    header("Location: login.html");
    exit();
}else{

// Database connection
$db = new mysqli('localhost', 'root', '', 'rent_right_rentals');
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

$user_id = $_SESSION['user_id'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tenant Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style type="text/css">
        /* General Styles */
body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    margin: 0;
    padding: 0;
    line-height: 1.6;
}

h1, h2 {
    color: #333;
}

nav {
    background-color: #2c3e50;
    padding: 1em 0;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

nav ul {
    list-style: none;
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 0;
}

nav ul li {
    margin: 0 1.5em;
}

nav ul li a {
    color: white;
    text-decoration: none;
    font-weight: bold;
    padding: 0.5em 1em;
    transition: background-color 0.3s, color 0.3s;
}

nav ul li a:hover {
    background-color: #34495e;
    border-radius: 4px;
    color: #ecf0f1;
}

#dashboard-content {
    max-width: 1200px;
    margin: 2.5em auto;
    padding: 0 2em;
}

section {
    background-color: white;
    padding: 2em;
    margin-bottom: 2em;
    border-radius: 8px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
}

form {
    display: flex;
    flex-direction: column;
}

form select, 
form textarea, 
form button {
    margin-bottom: 1.5em;
    padding: 0.75em;
    border: 1px solid #ccc;
    border-radius: 4px;
    font-size: 1em;
}

form button {
    background-color: #2c3e50;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s, box-shadow 0.3s;
}

form button:hover {
    background-color: #34495e;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

/* Specific Section Styles */
#quick-stats, 
#service-history, 
#payment-summary, 
#message-list {
    margin-top: 1.5em;
}

#quick-stats div, 
#service-history div, 
#payment-summary div, 
#message-list div {
    background-color: #ecf0f1;
    padding: 1.5em;
    margin-bottom: 1em;
    border-radius: 4px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
}

a.button {
    display: inline-block;
    padding: 0.75em 1.5em;
    background-color: #2c3e50;
    color: white;
    text-decoration: none;
    border-radius: 4px;
    text-align: center;
    transition: background-color 0.3s, box-shadow 0.3s;
}

a.button:hover {
    background-color: #34495e;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.2);
}

    </style>
</head>
<body>
    <nav>
        <ul>
            <li><a href="#home">Home</a></li>
            <li><a href="#services">Services</a></li>
            <li><a href="#payments">Payments</a></li>
            <li><a href="#messages">Messages</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </nav>
    
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h1>
    <br>
    <br>
    <br>
    <div id="dashboard-content">
        <section id="home">
            <h2>Dashboard Overview</h2>
            <div id="quick-stats">
                <!-- Quick stats will be loaded here by JavaScript -->
            </div>
        </section>

        <section id="services">
            <h2>Request Services</h2>
            <form method="POST" action="submit_service_request.php">
                <select name="service_type" required>
                    <option value="">Select Service</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="cleaning">Cleaning</option>
                    <option value="security">Security</option>
                </select>
                <textarea name="description" placeholder="Describe your request" required></textarea>
                <button type="submit">Submit Request</button>
            </form>
            <div id="service-history">
                <!-- Service history will be loaded here by JavaScript -->
            </div>
        </section>

        <section id="payments">
            <h2>Payments</h2>
            <div id="payment-summary">
                <!-- Payment summary will be loaded here by JavaScript -->
            </div>
            <a href="pay.php" class="button">Make a Payment</a>
        </section>

        <section id="messages">
            <h2>Messages</h2>
            <div id="message-list">
                <!-- Messages will be loaded here by JavaScript -->
            </div>
            <form action="send_messages.php" method="POST">
                <textarea name="message" placeholder="Type your message" required></textarea>
                <button type="submit">Send Message</button>
            </form>
        </section>
    </div>

    <script src="tenant_dashboard.js"></script>
</body>
</html>