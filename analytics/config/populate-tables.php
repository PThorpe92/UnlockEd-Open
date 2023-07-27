<?php
    namespace unlockedlabs\unlocked;
?>
<!DOCTYPE html>
<html lang="en">
<head>
        <title>Setting up database</title>
</head>

<body>
    <h3>Populating tables ...</h3>

<?php
// include database and object files
require_once 'database.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

function truncateTable($db, $tableName)
{

    $load = "TRUNCATE " . $tableName;
    $stmt = $db->prepare($load);
    try
    {
        $stmt->execute();
    }
    catch(\PDOException $e)
    {
        echo $e->getMessage();
        
    }
}

truncateTable($db, 'category_accessed');
   
?>

<br>...done.

</body>
</html>