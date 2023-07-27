<?php
    namespace unlockedlabs\unlocked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Dropping tables ...</h3>

<?php

require_once 'database.php';


//generate table if not exist
function dropTable($name)
{

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection();

    $dropTable = "DROP TABLE $name";
    $stmt = $db->prepare($dropTable);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

dropTable('category_accessed');
   
 ?>

<br>...done.

</body>
</html>