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

// include database and core files
require_once dirname(__FILE__).'/../config/database.php';

//generate table if not exist
function dropTables()
{

    // instantiate database and product object
    $database = new Database();
    $db = $database->getConnection(); 

    $tblListStmt = $db->query('SHOW Tables');
    $tblList=$tblListStmt->fetchAll();

    $database->disableFKChecks();

    foreach ($tblList as $key => $value) {
        $dropTable = "DROP TABLE {$value[0]}";
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

    $database->enableFKchecks();
}

dropTables();

?>

<br>...done.

</body>
</html>