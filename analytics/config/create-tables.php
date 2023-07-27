<?php
    namespace unlockedlabs\unlocked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Creating tables ...</h3>

<?php

require_once 'database.php';

//generate table if not exist
function createTable($name, $query)
{

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    $createTable = "CREATE TABLE IF NOT EXISTS $name($query)";
    $stmt = $db->prepare($createTable);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

createTable('category_accessed',
    'id VARCHAR(64) PRIMARY KEY,
    username TEXT,
    category_clicked TEXT,
    time_in TIMESTAMP,
    time_out TIMESTAMP,
    forced_logout TINYINT,
    access_id TINYINT,
    admin_id TINYINT,
    timestamp TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP');
 ?>

<br>...done.

</body>
</html>