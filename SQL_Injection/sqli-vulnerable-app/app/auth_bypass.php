<?php
    include("dbconfig.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

?>
<html>
    <head>
        <title>SQL Injection: Authentication Bypass Exercise</title>
        <link rel="stylesheet" href="login.css">
    </head>
    <body>

        <?php

            if(isset($_GET['logout'])) {
                session_destroy();
                header("Location: ./auth_bypass.php");
            }

            // someone
            if(isset($_POST['username']) && isset($_POST['password']) ) {

                $user = $_POST['username'];
                $pass = $_POST['password'];

                $sql = "select * from users where username='" . $user . "' and password='" . $pass ."'";

                $result=mysqli_query($conn, $sql) or die($conn->error);

                if(mysqli_num_rows($result) == 0) {

                    echo "<h3>Invalid Login!</h3>";
                    ?>

                    <?php

                } else {
                    $_SESSION["loggedin"] = true ;
                    $firstrow = mysqli_fetch_assoc($result);
                    $_SESSION["user"] = $firstrow["username"];
                }
            }

        ?>

        <?php
            if(!isset($_SESSION["loggedin"])) {
        ?>

        </br>
        </br>
        <div class="container">
        <form method="post" action="">
        <div id="div_login">
            <h1>Login</h1>
            <div>
                <input type="text" class="textbox" id="username" name="username" placeholder="Username" />
            </div>
            <div>
                <input type="password" class="textbox" id="password" name="password" placeholder="Password"/>
            </div>
            <div>
                <input type="submit" value="Submit"/>
            </div>
        </div>
        </form>
        </div>

        <?php
            } else {
                    ?>

                    <h3>Welcome back: <?php echo $_SESSION["user"]; ?></h3>
                    <a href="./auth_bypass.php?logout=true">Logout</a>

                    <?php
            }
        ?>


    </body>
</html>