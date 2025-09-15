<?php
// Session handling.
include 'session.php';

// Page name
$title = 'login';


// Default to invisible login error message.
$error = '&nbsp';


// User triggered login process.
if($_SERVER["REQUEST_METHOD"] == "POST") {

    // DB vars.
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'http');
    define('DB_PASSWORD', 'Cisco99');
    define('DB_DATABASE', 'training');

    // DB connection.
    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

    // Check DB errors. (This doesn't work)
    if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}

    // Prepare and execute SQL.
    $stmt = $conn->prepare('SELECT uuid,password_hash FROM staff WHERE username = ?');
    $stmt->bind_param('s', $_POST['username']);
    $stmt->execute();
    $stmt->store_result();

    // Verify student_uid.
    if ($stmt->num_rows > 0) {

        // Bind/fetch SQL results.
        $stmt->bind_result($uuid,$password_hash);
        $stmt->fetch();

        // Verify password.
        if ($_POST['password'] === $password_hash) {    //To secure, replace with if (password_verify($_POST['password'] === $password_hash))

            // Set session vars.
            session_regenerate_id();
            $_SESSION['loggedin'] = TRUE;
            $_SESSION['id'] = $uuid;

            // Log login.
            openlog('Staff-Training-App', LOG_CONS | LOG_NDELAY | LOG_PID, LOG_USER | LOG_PERROR);
            syslog(LOG_WARNING, "$uuid logged in.");
            closelog();

            // Redirect to home page.
            header("location: home.php");

        } else {
            $_SESSION['loggedin'] = FALSE;
            $error = "Your username and/or password is invalid.";
        }

    } else {
        $_SESSION['loggedin'] = FALSE;
        $error = "Your username and/or password is invalid.";
    }

    // Close DB connection.
    $stmt->close(); $conn->close();
}
?>

<html lang="en" class="h-100">
    <!-- Begin Head -->
    <?php include 'templates/head.inc'; ?>
    <!-- End Head -->

    <body class="d-flex flex-column h-100">
        <!-- Begin Navbar -->
        <?php include 'templates/navbar.inc'; ?>
        <!-- End Navbar -->

        <!-- Begin page content -->
        <main class="form-signin d-flex flex-column h-100 justify-content-center col-lg-6 col-xxl-4 mx-auto">
            <form action = "" method = "post">
                <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

                <div class="form-floating">
                    <input type="text" name="username" class="form-control" id="floatingInput" placeholder="e.g. jsmith">
                    <label for="floatingInput">username</label>
                </div>

                <div class="form-floating">
                    <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                    <label for="floatingPassword">Password</label>
                </div>
                <div style = "color:#cc0000"><?php echo $error; ?></div>
                <input class="w-100 btn btn-lg btn-primary" type="submit" value="Sign in"/>
            </form>
        </main>
        <!-- End page content -->

        <!-- Begin Page Footer -->
        <?php include 'templates/footer.inc'; ?>
        <!-- End Page Footer -->
    </body>
</html>
