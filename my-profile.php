<?php
    // Session handling.
    include 'session.php';

    // Page Title
    $title = 'Profile';



    // DB connection //
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'http');
    define('DB_PASSWORD', 'Cisco99');
    define('DB_DATABASE', 'training');

    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

    // Check DB errors. (This doesn't work)
    if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}


    // Run SQL Queries //

    // Get profile info
    $stmt = $conn->prepare('SELECT first_name, last_name FROM staff WHERE uuid = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($first_name, $last_name);
    $stmt->fetch();
    $stmt->close();

    // Get user's classes
    $stmt = $conn->prepare('
        SELECT class.class, class.start_date, class.end_date
        FROM class, enrolments
        WHERE (
            enrolments.staff_uid = ?
            AND class.uuid = enrolments.class_uid
        )
    ');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($class, $start_date, $end_date);
    $stmt->fetch();
    $stmt->close();

    // Close DB connection
    $conn->close();
?>


<!DOCTYPE html>
<html lang="en" class="h-100">
    <!-- Begin Head -->
    <?php include 'templates/head.inc'; ?>
    <!-- End Head -->


    <body class="d-flex flex-column h-100">


        <!-- Begin Navbar -->
        <?php include 'templates/navbar.inc'; ?>
        <!-- End Navbar -->


        <!-- Begin page content -->
        <main class="container d-flex flex-column">
        <div class="d-flex flex-column">
            <p class="h1">Profile</p>
        </div>
        <div class="d-flex flex-column">
            <h2 class="mt-5">Account Details</h2>
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">First Name:</th>
                        <td><?=htmlspecialchars($first_name, ENT_QUOTES)?></td>
                    </tr>
                    <tr>
                        <th scope="row">Last Name:</th>
                        <td><?=htmlspecialchars($last_name, ENT_QUOTES)?></td>
                    </tr>
                </tbody>
            </table>

            <h2 class="mt-5">Class details</h2>
            <table class="table">
                <tbody>
                    <tr>
                        <th scope="row">Class:</th>
                        <td><?=htmlspecialchars($class, ENT_QUOTES)?></td>
                    </tr>
                    <tr>
                        <th scope="row">Start Date:</th>
                        <td><?=htmlspecialchars($start_date, ENT_QUOTES)?></td>
                    </tr>
                    <tr>
                        <th scope="row">End Date:</th>
                        <td><?=htmlspecialchars($end_date, ENT_QUOTES)?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        </main>
        <!-- End page content -->


    <!-- Begin Page Footer -->
    <?php include 'templates/footer.inc'; ?>
    <!-- End Page Footer -->


    </body>
</html>
