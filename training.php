<?php
    // Session handling.
    include 'session.php';

    // Page Title
    $title = 'Example Page';



    // DB connection //
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'http');
    define('DB_PASSWORD', 'Cisco99');
    define('DB_DATABASE', 'training');

    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

    if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}



    // Get profile info
    $stmt = $conn->prepare('SELECT pre_quiz, training, post_quiz FROM enrolments WHERE staff_uid = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($pre_quiz, $training, $post_quiz);
    $stmt->fetch();
    $stmt->close();



    // If user not allowed in this page, redirect home //
    if (!($pre_quiz == 1 && $training == 0 && $post_quiz == 0)) {
        header("location: home.php");
    }



    // Set Training as Complete in DB //
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submitButton"])) {
        $stmt = $conn->prepare('UPDATE enrolments SET training = "1" WHERE staff_uid = ?');
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $stmt->close();

        $conn->close();
        header("Location: home.php");
    }


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
            <div class="flex-row">
                <h1>Header</h1> <!-- All but the first h1 in 'main' must have the mt-5 class. -->
                <p class="lead">Lead/subheading</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea</p>
            </div>
            <div class="flex-row">
                <h1 class="mt-5">Header</h1>
                <p class="lead">Lead/subheading</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea</p>
            </div>
            <div class="flex-row">
                <h1 class="mt-5">Header</h1>
                <p class="lead">Lead/subheading</p>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea</p>
            </div>
            <div class="flex-row justify-content-center mx-auto">
                <form method="post" >
                    <button type="submit" name="submitButton" class="btn btn-primary">Mark as Complete</button>
                </form>
            </div>
        </main>
        <!-- End page content -->

        <!-- Begin Page Footer -->
        <?php include 'templates/footer.inc'; ?>
        <!-- End Page Footer -->
    </body>
</html>
