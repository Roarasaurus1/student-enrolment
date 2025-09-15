<?php include 'session.php'; ?>
<?php $title = 'Home'; ?>

<?php

    // DB connection //
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'http');
    define('DB_PASSWORD', 'Cisco99');
    define('DB_DATABASE', 'training');

    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

    // Check DB errors.
    if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}



    // Run SQL Queries //

    // Get profile info
    $stmt = $conn->prepare('SELECT pre_quiz, training, post_quiz FROM enrolments WHERE staff_uid = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($pre_quiz, $training, $post_quiz);
    $stmt->fetch();
    $stmt->close();

    // Close DB connection
    $conn->close();



    // Verify and Set Quiz Completion Status //

    // Set default button statuses
    $pre_quiz_btn_status = "disabled";
    $training_btn_status = "disabled";
    $post_quiz_btn_status = "disabled";

    // Enable pre-quiz button
    if ($pre_quiz == 0 && $training == 0 && $post_quiz == 0 ) {
        $pre_quiz_btn_status = "";
    }
    // Enabled training button
    if ($pre_quiz == 1 && $training == 0 && $post_quiz == 0 ) {
        $training_btn_status = "";
    }
    // Enable post-quiz button
    if ($pre_quiz == 1 && $training == 1 && $post_quiz == 0 ) {
        $post_quiz_btn_status = "";
    }

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
        <main class="d-flex flex-column h-100 justify-content-center col-lg-6 col-xxl-4 mx-auto">
            <div class="d-grid gap-4">
                <a href="pre-quiz.php" class="btn btn-primary <?php echo $pre_quiz_btn_status ?>">Pre-Quiz</a>
                <a href="training.php" class="btn btn-primary <?php echo $training_btn_status ?>">Training</a>
                <a href="post-quiz.php" class="btn btn-primary <?php echo $post_quiz_btn_status ?>">Post-Quiz</a>
                <a href="my-profile.php" class="btn btn-secondary">My Profile</a>
            </div>
        </main>
        <!-- End page content -->

        <!-- Begin Page Footer -->
        <?php include 'templates/footer.inc'; ?>
        <!-- End Page Footer -->
    </body>
</html>
