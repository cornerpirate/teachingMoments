<?php
    include("dbconfig.php");

    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
?>
<html>
    <head>
        <title>SQL Injection: Injecting into String Field</title>
    </head>
    <body>
    <h3><SQL Injection: Injecting into String Field/h3>
    </br>

    <form method="GET">
        NAME: <input type="text" name="name" id="name"><input type="submit" value="search">
    </form>

    </br>

    <?php

        $sql = "SELECT * FROM people";
        if(isset($_GET['name']) && $_GET['name'] != "") {
            $sql = $sql . " WHERE name='" .$_GET['name'] . "'" ;
        }
        echo "[*] SQL: " . $sql  . "</br></br>" ;

        $result = $conn->query($sql) ;

        if (!$result) {
            $message  = 'Invalid query: ' . mysqli_error($conn) . "\n";
            $message .= 'Whole query: ' . $sql;
            die($message);
        }

    ?>

    <table>
        <tr>
            <td>Id</td>
            <td>Name</td>
            <td>Age</td>
        </tr>

        <?php
            // Print any rows from result set
            while($row = $result->fetch_assoc()) {
                echo "<tr>" ;
                echo '  <td>' . $row['id'] . '</td>' ;
                echo '  <td>' . $row['name'] . '</td>' ;
                echo '  <td>' . $row['age'] . '</td>' ;
                echo "</tr>" ;
            }
        ?>

    </table>

    </body>
</html>