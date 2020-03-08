<?php
    include("dbconfig.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
?>
<html>
    <head>
        <title>SQL Injection: Blind Injection into Numeric Field</title>
    </head>
    <body>
    <h3>SQL Injection: Blind Injection into Numeric Field</h3>
    </br>

    <form method="GET">
        COUNT OF PEOPLE WITH AGE LESS THAN?: <input type="text" name="age" id="age"><input type="submit" value="search">
    </form>

    </br>

    <?php

        $sql = "SELECT * FROM people";
        if(isset($_GET['age']) && $_GET['age'] != "") {
            $sql = $sql . " WHERE age<" . $_GET['age']  ;
        } else {
            die("No age specified");
        }
        //echo "[*] SQL: " . $sql  . "</br></br>" ;

        $result = $conn->query($sql) ;

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        printf("Result set has %d rows.\n", $row_cnt);

        /* close result set */
        $result->close();
        $conn->close();
    ?>


    </body>
</html>