<?php

    try {
        system("mysql -u root < /app/content.sql");
        echo "Database Regenerated";
    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

?>