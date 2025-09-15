<?php
    // Session handling.
    include 'session.php';

    // Page Title
    $title = 'post-quiz';



    // DB connection //
    define('DB_SERVER', 'localhost');
    define('DB_USER', 'http');
    define('DB_PASSWORD', 'Cisco99');
    define('DB_DATABASE', 'training');

    $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);

    // Check DB errors.
    if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}



    // Run SQL Queries //
    $stmt = $conn->prepare('SELECT pre_quiz, training, post_quiz FROM enrolments WHERE staff_uid = ?');
    $stmt->bind_param('s', $_SESSION['id']);
    $stmt->execute();
    $stmt->bind_result($pre_quiz, $training, $post_quiz);
    $stmt->fetch();
    $stmt->close();

    // Close DB connection.
    $conn->close();



   // If user not allowed in this page, redirect home.
    if (!($pre_quiz == 1 && $training == 1 && $post_quiz == 0)) {
        header("location: home.php");
    }



    // Question Array //
    $Questions = array(
        1 => array(
            'Question' => 'CSS stands for',
            'Answers' => array(
                'A' => 'First answer of First qustion',
                'B' => 'Cascading Style Sheets',
                'C' => 'Third answer of First question'
            ),
            'CorrectAnswer' => 'B'
        ),
        2 => array(
            'Question' => 'Second question',
            'Answers' => array(
                'A' => 'First answer of Second question',
                'B' => 'Second answer Second question',
                'C' => 'Third answer Second question'
            ),
            'CorrectAnswer' => 'C'
        ),
        3 => array(
            'Question' => 'Third question',
            'Answers' => array(
                'A' => 'First answer of Third question',
                'B' => 'Second answer of Third question',
                'C' => 'Third answer of Third question'
            ),
            'CorrectAnswer' => 'B'
        )
    );



    // Set Quiz as Complete in DB //
    function update_quiz_db() {

        $conn = new mysqli(DB_SERVER,DB_USER,DB_PASSWORD,DB_DATABASE);
        if ($conn->connect_errno) {exit('Failed to connect to MariaDB: ' . $conn->connect_error);}

        // Run SQL Queries //
        $stmt = $conn->prepare('UPDATE enrolments SET post_quiz = 1 WHERE staff_uid = ?');
        $stmt->bind_param('s',$_SESSION['id']);
        $stmt->execute();
        $stmt->close();

        // Close DB connection.
        $conn->close();
    }
?>



<!DOCTYPE html>
<html lang="en" class="h-100">


    <!-- Begin Head -->
    <?php include 'templates/head.inc'; ?>
    <!-- End Head -->


    <body class="d-flex flex-column">


    <!-- Begin Navbar -->
    <?php include 'templates/navbar.inc'; ?>
    <!-- End Navbar -->


    <!-- Begin page content -->
    <main class="container d-flex flex-column">


    <!-- Begin questions -->
    <?php
    if (isset($_POST['answers'])){
        $Answers = $_POST['answers']; // Get submitted answers.
        update_quiz_db(); // Set pre-quiz as complete in db.
    ?>
        <div class="flex-row">
            <h1>Quiz Results</h1> <!-- All but the first h1 in 'main' must have the mt-5 class. -->
            <br />
    <?php
        foreach ($Questions as $QuestionNo => $Value){

            echo $Value['Question'].'<br />';

            if ($Answers[$QuestionNo] != $Value['CorrectAnswer']){
                echo '<span style="color: red;">'.$Value['Answers'][$Answers[$QuestionNo]].'</span>'; // Replace style with a class
            } else {
                echo '<span style="color: green;">'.$Value['Answers'][$Answers[$QuestionNo]].'</span>'; // Replace style with a class
            }
            echo '<br /><hr>';
        }
    echo '</div>';
    } else { ?>
        <form action="post-quiz.php" method="post" id="quiz">
        <?php foreach ($Questions as $QuestionNo => $Value){ ?>
            <div class="flex-row" id="question-<?php echo $QuestionNo?>" >
            <h1 class="mt-5">Question<?php echo $QuestionNo ?></h1>
            <p><?php echo $Value['Question']; ?></p>
            <?php
            foreach ($Value['Answers'] as $Letter => $Answer){
                $Label = 'question-'.$QuestionNo.'-answers-'.$Letter;
	         ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="answers[<?php echo $QuestionNo; ?>]" id="<?php echo $Label; ?>" value="<?php echo $Letter; ?>" />
                    <label class="form-check-label" for="<?php echo $Label; ?>"><?php echo $Letter; ?>) <?php echo $Answer; ?> </label>
                </div>
            <?php } ?>
            </div>
        <?php } ?>
           <h1 class="mt-5"></h1>
           <input type="submit" value="Submit Quiz" class="btn btn-primary mb-3/>
	    </form>
    <?php } ?>
    <!-- End questions -->


    </main>
    <!-- End page content -->


    <!-- Begin Page Footer -->
    <?php include 'templates/footer.inc'; ?>
    <!-- End Page Footer -->


    </body>
</html>
