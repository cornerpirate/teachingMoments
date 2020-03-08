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
        <title>SQL Injection: Blind Injection into String Field</title>
    </head>
    <body>
    <h3>SQL Injection: Blind Injection into String Field</h3>

    <p>For ease the people table contains this data</p>
    <pre>
    Id 	Name 	Age
    1 	Autry 	33
    2 	Tab 	43
    3 	Lila 	25
    </pre>

    </br>

    <form method="GET">
        NAME: <input type="text" name="name" id="name"><input type="submit" value="search">
    </form>

    </br>

    <?php

        $sql = "SELECT * FROM people";
        if(isset($_GET['name']) && $_GET['name'] != "") {
            $sql = $sql . " WHERE name='" .$_GET['name'] . "'" ;
        } else {
            die("No name specified");
        }
        //echo "[*] SQL: " . $sql  . "</br></br>" ;

        $starttime = microtime(true);

        $result = $conn->query($sql) ;

        /* determine number of rows result set */
        $row_cnt = $result->num_rows;

        printf("<p><b>Note</b>: info below will not be available in truly blind SQL i, but it helps here explain things");
        printf("<p>Result set has %d rows.\n", $row_cnt);

        $endtime = microtime(true);
        $duration = $endtime - $starttime; //calculates total time taken

        printf("<p>Result took %d second(s).\n", $duration);

        /* close result set */
        $result->close();
        $conn->close();



    ?>


    </body>
</html>